<?php

class priceParserPriceDisableProcessor extends modObjectProcessor
{
    public $objectType = 'priceParserPrice';
    public $classKey = 'priceParserPrice';
    public $languageTopics = ['priceparser'];
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('priceparser_price_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var priceParserPrice $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('priceparser_price_err_nf'));
            }

            $object->set('active', false);
            $object->save();
        }

        return $this->success();
    }

}

return 'priceParserPriceDisableProcessor';
