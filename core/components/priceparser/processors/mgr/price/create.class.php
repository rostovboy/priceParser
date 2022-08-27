<?php

class priceParserPriceCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'priceParserPrice';
    public $classKey = 'priceParserPrice';
    public $languageTopics = ['priceparser'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $data['createdon'] = strftime('%Y-%m-%d %H:%M:%S', strtotime("now"));
        $product_id = (int)$this->getProperty('product_id');

        if (!$product = $this->modx->getObject('priceParserProduct', $product_id)) return false;

        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_price_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'product_id' => $product->id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_price_err_ae'));
        }

        $this->object->fromArray($data);

        return parent::beforeSet();
    }

}

return 'priceParserPriceCreateProcessor';