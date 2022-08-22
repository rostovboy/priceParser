<?php

class priceParserProductUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'priceParserProduct';
    public $classKey = 'priceParserProduct';
    public $languageTopics = ['priceparser'];
    //public $permission = 'save';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $sku = trim($this->getProperty('sku'));
        if (empty($id)) {
            return $this->modx->lexicon('priceparser_product_err_ns');
        }

        if (empty($sku)) {
            $this->modx->error->addField('sku', $this->modx->lexicon('priceparser_product_err_sku'));
        } elseif ($this->modx->getCount($this->classKey, ['sku' => $sku, 'id:!=' => $id])) {
            $this->modx->error->addField('sku', $this->modx->lexicon('priceparser_product_err_ae'));
        }

        return parent::beforeSet();
    }
}

return 'priceParserProductUpdateProcessor';
