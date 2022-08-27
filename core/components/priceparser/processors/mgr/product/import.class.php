<?php

class priceParserProductImportProcessor extends modProcessor
{
    protected $priceParser; // объявляем, чтобы использовать функции из основного класса
    public $languageTopics = ['priceparser:default'];

    public function initialize()
    {
        $this->priceParser = $this->modx->getService('priceParser', 'priceParser', MODX_CORE_PATH . 'components/priceparser/model/');

        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }

    public function clearUploadDir()
    {
        $uploadpath = MODX_ASSETS_PATH . 'components/priceparser/upload/';
        return $this->modx->cacheManager->deleteTree($uploadpath, array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array()));
    }

    public function process()
    {
        /**
         * Обработчик загрузки файла импорта
         */
        $this->clearUploadDir();

        if (!$_FILES['file']) {
            return $this->failure('error');
        }

        $uploadpath = MODX_ASSETS_PATH . 'components/priceparser/upload/';
        $filename = str_replace(' ', '_', $_FILES['file']['name']);
        $file = $uploadpath . $filename;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            return $this->failure('Загрузите файл импорта!');
        }

        // $this->modx->log(1, $_FILES['file']['name']);

        /**
         * Загружаем библиотеку импорта из XSLX
         */
        if (!class_exists('PhpSpreadsheet')) {
            require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php';
        }
        if (empty($this->reader)) {
            $this->reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        // Все готово — загружаем
        $spreadsheet = $reader->load($file);

        // Начинаем работать с массивом листов
        $sheets = array();
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $sheets[] = $worksheet->toArray();
        }

        /**
         * Подгружаем настройки импорта компонента
         */
        // С какой строки начинаем импорт (по-умолчанию 3 строка, нумерация с первой строки)
        $start = $this->modx->getOption('priceparser_xlsx_import_start');

        // Массив ключей импорта
        $col_names = [
            'name',
            'sku',
            'category',
            'sb',
            'tc',
            'rrc',
            'margin',
            'minrent',
            'minprice',
            'ozlink1',
            'ozlink2',
            'ozlink3',
            'ozlink4',
            'ozlink5',
            'ozmylink',
            'oznewprice',
            'ymlink1',
            'ymlink2',
            'ymlink3',
            'ymlink4',
            'ymlink5',
            'ymmylink',
            'ymnewprice',
        ];


        $import_data = [];
        foreach ($sheets as $sh_num => $sheet) {
            // Обрабатываем только первый лист
            if ($sh_num == 0) {
                foreach ($sheet as $r_num => $row) {
                    // Пропускаем строки с заголовками, номер начальной строки берем из настроек компонента
                    if ($r_num + 1 >= $start) {
                        // Получаем массив одной строки-продукта
                        $row_data = [];
                        foreach ($row as $c_num => $col) {
                            // Ограничиваем количество собираемых колонок количеством ключей импорта
                            if ($c_num + 1 <= count($col_names)) {
                                $row_data[] = $col;
                            } else {
                                break;
                            }
                        }
                        if (count($col_names) == count($row_data)) {
                            $import_data[] = array_combine($col_names, $row_data);
                        }
                    }
                }
            } else {
                break;
            }
        }


        /**
         * Фильтруем данные
         */
        foreach ($import_data as $key => $value) {
            foreach ($value as $k => $v) {
                $v = preg_replace('|[\s]+|s', ' ', $v);
                $v = str_replace('\xc2\xa0', ' ', $v);
                $v = str_replace('( ', '(', $v);
                $v = str_replace(' )', ')', $v);
                if (in_array($k, [
                    'sb',
                    'tc',
                    'rrc',
                    'margin',
                    'minprice',
                    'oznewprice',
                    'ymnewprice',
                ])) {
                    $v = str_replace(',', '.', $v);
                    $v = str_replace(' ', '', $v);
                }
                if (in_array($k, ['minrent'])) {
                    $v = str_replace(',', '.', $v);
                    $v = str_replace('%', '', $v);
                }

                $value[$k] = $v;
            }
            $import_data[$key] = $value;
        }

        //$this->modx->log(1, print_r($import_data, 1));

        /**
         * Записываем полученные данные
         */
        $created = [];
        $updated = [];
        $products = [];
        $errors = [];
        $warnings = [];


        // Импортируем каждую строку продукта
        foreach ($import_data as $k => $data) {
            // Ищем совпадение с базой товаров по sku
            if ($ms_product = $this->modx->getObject('msProductData', array('sku' => $data['sku']))) {
                $data = array_merge($data, array('resource_id' => $ms_product->id));
            } else {
                $warnings[] = 'Not found product with sku ' . $data['sku'];
                $this->modx->log(1, 'Not found product with sku ' . $data['sku']);
            }
            // Создаем продукт
            if (!$product = $this->modx->getObject('priceParserProduct', ['sku' => $data['sku']])) {
                $response = $this->modx->runProcessor('mgr/product/create', $data,
                    array(
                        'processors_path' => $this->modx->getOption('core_path') . 'components/priceparser/processors/'
                    ));
                if ($response->isError()) {
                    $errors[] = 'Create Product Error ' . $response->getMessage();
                    $this->modx->log(1, 'Create Product Error ' . $response->getMessage());
                    return $response->getMessage();
                }
                $created[] = $product_id = $response->response['object']['id'];
            }
            // Обновляем продукт
            else {
                $updated[] = $product_id = $product->id;
                $data = array_merge($data, ['id' => $product_id]);
                $response = $this->modx->runProcessor('mgr/product/update', $data,
                    array(
                        'processors_path' => $this->modx->getOption('core_path') . 'components/priceparser/processors/'
                    ));
                if ($response->isError()) {
                    $errors[] = 'Update Product Error ' . $response->getMessage();
                    $this->modx->log(1, 'Update Product Error ' . $response->getMessage());
                    return $response->getMessage();
                }
            }

            //$this->modx->log(1, print_r($data, 1));

            // Для каждого продукта получаем все ссылки на парсинг цен затем импортируем их в priceParserPrice
            $price_data = [];
            $i = -1;
            foreach ($data as $key => $value) {
                // OZON
                if ($key == 'ozlink1') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'ЦКО 1';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ozlink2') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'ЦКО 2';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ozlink3') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'ЦКО 3';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ozlink4') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'ЦКО 4';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ozlink5') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'ЦКО 5';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ozmylink') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 1;
                    $price_data[$i]['name'] = 'Наша OZON';
                    $price_data[$i]['link'] = $value;
                }
                // ЯндексМаркет
                if ($key == 'ymlink1') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'ЦКЯ 1';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ymlink2') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'ЦКЯ 2';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ymlink3') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'ЦКЯ 3';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ymlink4') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'ЦКЯ 4';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ymlink5') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'ЦКЯ 5';
                    $price_data[$i]['link'] = $value;
                }
                if ($key == 'ymmylink') {
                    $i++;
                    $price_data[$i]['product_id'] = $product_id;
                    $price_data[$i]['marketplace_id'] = 2;
                    $price_data[$i]['name'] = 'Наша Маркет';
                    $price_data[$i]['link'] = $value;
                }
            }

            //$this->modx->log(1, print_r($price_data, 1));

            foreach ($price_data as $price_v) {
                // Импортируем ссылки
                if (!$price = $this->modx->getObject('priceParserPrice', ['product_id' => $price_v['product_id'], 'name' => $price_v['name']])) {
                    $response = $this->modx->runProcessor('mgr/price/create', $price_v,
                        array(
                            'processors_path' => $this->modx->getOption('core_path') . 'components/priceparser/processors/'
                        ));
                    if ($response->isError()) {
                        $errors[] = 'Create Price Error ' . $response->getMessage();
                        $this->modx->log(1, 'Create Price Error ' . $response->getMessage());
                        return $response->getMessage();
                    }
                    $price_id = $response->response['object']['id'];
                }
                // Обновляем ссылки
                else {
                    $price_v = array_merge($price_v, ['id' => $price->id]);
                    $response = $this->modx->runProcessor('mgr/price/update', $price_v,
                        array(
                            'processors_path' => $this->modx->getOption('core_path') . 'components/priceparser/processors/'
                        ));
                    if ($response->isError()) {
                        $errors[] = 'Update Price Error ' . $response->getMessage();
                        $this->modx->log(1, 'Update Price Error ' . $response->getMessage());
                        return $response->getMessage();
                    }
                }
            }
        }

        $summary = [
            'Файл' => $_FILES['file']['name'],
            'Всего обработано строк' => count($import_data),
            'Создано' => count($created),
            'Обновлено' => count($updated),
            'Дата импорта' => date('Y-m-d H:i:s'),
            'Автор' => $this->modx->user->get('id'),
            'Импортированные товары' => $products,
            'Ошибки' => $errors,
            'Предупреждения' => $warnings,
        ];

        $this->modx->log(1, print_r($summary, 1));

        return $this->success('success');
    }



}

return 'priceParserProductImportProcessor';