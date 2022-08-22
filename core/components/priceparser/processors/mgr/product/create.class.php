<?php

class priceParserProductCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'priceParserProduct';
    public $classKey = 'priceParserProduct';
    public $languageTopics = ['priceparser'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $sku = trim($this->getProperty('sku'));
        if (empty($sku)) {
            $this->modx->error->addField('sku', $this->modx->lexicon('priceparser_product_err_sku'));
        } elseif ($this->modx->getCount($this->classKey, ['sku' => $sku])) {
            $this->modx->error->addField('sku', $this->modx->lexicon('priceparser_product_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'priceParserProductCreateProcessor';