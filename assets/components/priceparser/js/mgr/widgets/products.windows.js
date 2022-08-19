priceParser.window.CreateProduct = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-product-window-create';
    }
    Ext.applyIf(config, {
        title: _('priceparser_product_create'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/product/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.CreateProduct.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.CreateProduct, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_product_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_product_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-product-window-create', priceParser.window.CreateProduct);


priceParser.window.UpdateProduct = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-product-window-update';
    }
    Ext.applyIf(config, {
        title: _('priceparser_product_update'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/product/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.UpdateProduct.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.UpdateProduct, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_product_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_product_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-product-window-update', priceParser.window.UpdateProduct);