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
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_product_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_product_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'priceParserProductCreateProcessor';