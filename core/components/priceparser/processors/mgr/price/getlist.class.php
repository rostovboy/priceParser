<?php

class priceParserPriceGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'priceParserPrice';
    public $classKey = 'priceParserPrice';
    public $defaultSortField = 'marketplace_id';
    public $defaultSortDirection = 'ASC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where(array('product_id' => $this->getProperty('product_id')));

        $this->modx->log(1, $this->getProperty('product_id'));

        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('priceparser_price_update'),
            //'multiple' => $this->modx->lexicon('priceparser_prices_update'),
            'action' => 'updatePrice',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('priceparser_price_enable'),
                'multiple' => $this->modx->lexicon('priceparser_prices_enable'),
                'action' => 'enablePrice',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('priceparser_price_disable'),
                'multiple' => $this->modx->lexicon('priceparser_prices_disable'),
                'action' => 'disablePrice',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('priceparser_price_remove'),
            'multiple' => $this->modx->lexicon('priceparser_prices_remove'),
            'action' => 'removePrice',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'priceParserPriceGetListProcessor';