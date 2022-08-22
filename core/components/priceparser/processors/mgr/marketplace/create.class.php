<?php

class priceParserMarketplaceCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'priceParserMarketplace';
    public $classKey = 'priceParserMarketplace';
    public $languageTopics = ['priceparser'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_marketplace_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_marketplace_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'priceParserMarketplaceCreateProcessor';