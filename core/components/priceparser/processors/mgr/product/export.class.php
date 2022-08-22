<?php

require dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class priceParserReportExportProcessor extends modProcessor
{
    public $classKey = 'priceParser';
    public $languageTopics = ['priceparser'];


    public function clearExportDir()
    {
        $uploadpath = MODX_ASSETS_PATH . 'components/priceparser/export/';
        return $this->modx->cacheManager->deleteTree($uploadpath, array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array()));
    }

    /**
     * @param int $step
     * @param int $offset
     * @return array
     */
    public function export_report($step = 0, $offset = 0): array
    {
        $q = $this->modx->newQuery('modResource');
        $q->limit($step, $offset);
        $q->where(array('class_key' => 'msProduct', 'parent:NOT IN' => [2,12372]));
        $q->leftJoin('msProductData', 'Data');
        $q->select(array('modResource.id', 'modResource.pagetitle', 'modResource.longtitle', 'modResource.published', 'modResource.market'));
        $q->select(array('Data.price', 'Data.quantity', 'Data.sku'));

        $ps = array();
        $i = -1;
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $i++;
                // product
                $ps[$i] = $row;
                $res_id = $row['id'];
                // discount
                if ($res_coupon = $this->modx->getObject('mspcResource', array('resource_id' => $res_id))) {
                    if ($coupon = $this->modx->getObject('mspcCoupon', array('id' => $res_coupon->get('coupon_id'), 'active' => 1))) {
                        $ps[$i]['coupon'] = $coupon->get('code');
                        $ps[$i]['discount'] = $coupon->get('discount');
                        $ps[$i]['ends'] = $coupon->get('ends');
                        $ps[$i]['discount_price'] = '';
                        $ps[$i]['url'] = $url = $this->modx->makeUrl($res_id, 'web');
                        // discount price
                        if (stripos(trim($coupon->get('discount')), '%')) {
                            $ps[$i]['discount_price'] = $row['price'] - ($row['price'] * ($coupon->get('discount') / 100));
                        } else {
                            $ps[$i]['discount_price'] = $row['price'] - $coupon->get('discount');
                        }
                    }
                }

                // images
                $files = [];
                $qf = $this->modx->newQuery('msProductFile');
                $qf->limit(0);
                $qf->where(array('product_id' => $res_id, 'parent' => 0));
                $qf->select(array('msProductFile.url', 'msProductFile.description', 'msProductFile.rank'));
                if ($qf->prepare() && $qf->stmt->execute()) {
                    while ($row = $qf->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $site_url = $this->modx->getOption('site_url');
                        if (substr($site_url, -1) == '/') $site_url = mb_substr($site_url, 0, -1);
                        if ($row['rank'] == 0) $files['default'] = $site_url . $row['url'];
                        if ($row['description'] == 'Market') $files['market'] = $site_url . $row['url'];
                    }
                }
                if ($files) {
                    $ps[$i]['files'] = $files;
                }
                // mxpromos
                $promos = [];
                $qp = $this->modx->newQuery('mxPromoProduct');
                $qp->limit(0);
                $qp->where(array('product_id' => $res_id));
                $qp->innerJoin('mxPromoAction', 'Action');
                $qp->where(array('Action.active' => 1, 'Action.market' => 1));
                $qp->select(array('mxPromoProduct.action_id'));
                $qp->select(array('Action.name'));
                if ($qp->prepare() && $qp->stmt->execute()) {
                    while ($row = $qp->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $promos[] = $row['name'];
                    }
                }
                if ($promos) {
                    $ps[$i]['promos'] = $promos;
                }
            }
        }

        $output = [];
        foreach ($ps as $k => $product) {
            $output[$k]['ID товара'] = $product['id'];
            $output[$k]['UUID товара из 1С'] = $product['sku'];
            $output[$k]['Название товара из 1С'] = $product['pagetitle'];
            $output[$k]['Цена'] = $product['price'];
            $output[$k]['Кол-во'] = $product['quantity'];
            $output[$k]['Название для Яндекс-маркета'] = $product['longtitle'];
            $output[$k]['Фото основное'] = $product['files']['default'];
            $output[$k]['Фото для маркета'] = $product['files']['market'];
            $output[$k]['Опубликован'] = $product['published'] ? 'Да' : 'Нет';
            $output[$k]['Маркет'] = $product['market'] ? 'Да' : 'Нет';
            $output[$k]['Промокод'] = $product['coupon'];
            $output[$k]['Скидка по промокоду'] = $product['discount'];
            $output[$k]['Цена со скидкой'] = $product['discount_price'];
            $output[$k]['Промоакции на маркете'] = $product['promos'];
            $output[$k]['Ссылка'] = $this->modx->makeUrl($product['id'], 'web', '', 'full');
        }

        return $output;
    }


    public function process()
    {
        // очищаем директорию экспорта
        $this->clearExportDir();

        // Конфигурируем имя файла и директорию экспорта
        $site_url = $this->modx->getOption('site_url');
        $basepath = MODX_BASE_PATH;
        $exportpath = MODX_ASSETS_PATH . 'components/priceparser/export/';
        $urlpath = str_replace($basepath, '', $exportpath);
        $filename = 'export-' . date("d-m-Y") . '.xlsx';


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Получаем данные
        $export_data = $this->export_report(8000, 0);

        $titles = [
            'ID товара', 'UUID товара из 1С', 'Название товара из 1С', 'Цена', 'Кол-во', 'Название для Яндекс-маркета', 'Фото основное', 'Фото для маркета',
            'Опубликован', 'Маркет', 'Промокод', 'Скидка по промокоду', 'Цена со скидкой', 'Промоакции на маркете', 'Ссылка'
        ];
        $i = 1;
        $range = range('A', 'Z');
        $data = [];
        foreach ($titles as $k => $v) {
            $data[$range[$k] . $i] = $v;
        }
        // Записываем заголовки
        foreach ($data as $key => $value) {
            $sheet->setCellValue($key, $value);
        }

        // Записываем данные $cell = 0, если без заголовков
        $cell = 1;
        foreach ($export_data as $product) {
            $i = 0;
            $cell++;
            $range = range('A', 'Z');
            foreach ($product as $val) {
                $sheet->setCellValue($range[$i++] . $cell, $val);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($exportpath . $filename);


        $output = array(
            'report' => $site_url . $urlpath . $filename,
        );

        return $this->success($this->modx->lexicon('priceparser_success_export'), $output);

    }
}

return 'priceParserReportExportProcessor';