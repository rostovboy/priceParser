<?php

class priceParserPriceUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'priceParserPrice';
    public $classKey = 'priceParserPrice';
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
        $data['updatedon'] = strftime('%Y-%m-%d %H:%M:%S', strtotime("now"));
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('priceparser_price_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_price_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('priceparser_price_err_ae'));
        }

        $this->object->fromArray($data);

        return parent::beforeSet();
    }
}

return 'priceParserPriceUpdateProcessor';
