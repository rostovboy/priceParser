<?php

class priceParserProductGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'priceParserProduct';
    public $classKey = 'priceParserProduct';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
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

        //$this->modx->log(1, print_r($this->modx->user->getUserGroupNames(), 1));
        $groups = $this->modx->user->getUserGroupNames();

        if (!in_array('Administrator', $groups)) {
            if (!in_array('Marketologs', $groups)) {
                return $this->modx->lexicon('access_denied');
            }
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
            'title' => $this->modx->lexicon('priceparser_product_update'),
            //'multiple' => $this->modx->lexicon('priceparser_products_update'),
            'action' => 'updateProduct',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('priceparser_product_enable'),
                'multiple' => $this->modx->lexicon('priceparser_products_enable'),
                'action' => 'enableProduct',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('priceparser_product_disable'),
                'multiple' => $this->modx->lexicon('priceparser_products_disable'),
                'action' => 'disableProduct',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('priceparser_product_remove'),
            'multiple' => $this->modx->lexicon('priceparser_products_remove'),
            'action' => 'removeProduct',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'priceParserProductGetListProcessor';