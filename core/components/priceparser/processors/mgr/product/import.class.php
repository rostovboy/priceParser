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
            'ozcurprice',
            'oznewprice',
            'ymlink1',
            'ymlink2',
            'ymlink3',
            'ymlink4',
            'ymlink5',
            'ymcurprice',
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
                $this->modx->log(1, $v);
                $v = preg_replace('|[\s]+|s', ' ', $v);
                $v = str_replace('\xc2\xa0', ' ', $v);
                $v = str_replace('( ', '(', $v);
                $v = str_replace(' )', ')', $v);
                if (in_array($k, ['sb', 'tc', 'rrc', 'margin', 'minprice', 'ozcurprice', 'oznewprice', 'ymcurprice', 'ymnewprice'])) {
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

        $this->modx->log(1, print_r($import_data, 1));

        /**
         * Записываем полученные данные
         */
        $created = [];
        $updated = [];
        $products = [];
        $errors = [];
        $warnings = [];


        /*foreach ($import_data as $v) {

        }*/




        return $this->success('success');
    }


}

return 'priceParserProductImportProcessor';