<?php

class priceParserProductDisableProcessor extends modObjectProcessor
{
    public $objectType = 'priceParserProduct';
    public $classKey = 'priceParserProduct';
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
            return $this->failure($this->modx->lexicon('priceparser_product_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var priceParserProduct $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('priceparser_product_err_nf'));
            }

            $object->set('active', false);
            $object->save();
        }

        return $this->success();
    }

}

return 'priceParserProductDisableProcessor';
