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
            allowBlank: true,
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_sku'),
            name: 'sku',
            id: config.id + '-sku',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_category'),
            name: 'category',
            id: config.id + '-category',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_sb'),
            name: 'sb',
            id: config.id + '-sb',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_tc'),
            name: 'tc',
            id: config.id + '-tc',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_rrc'),
            name: 'rrc',
            id: config.id + '-rrc',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_margin'),
            name: 'margin',
            id: config.id + '-margin',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_minrent'),
            name: 'minrent',
            id: config.id + '-minrent',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_minprice'),
            name: 'minprice',
            id: config.id + '-minprice',
            anchor: '99%',
            allowBlank: true,
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
        width: 1000,
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
            xtype: 'modx-tabs',
            cls: 'priceparser-window-tabs',
            deferredRender: false,
            border: true,
            items: [{
                title: _('priceparser_product'),
                hideMode: 'offsets',
                layout: 'form',
                border: true,
                items: this.getTabProduct(config),
            }, {
                title: _('priceparser_product_prices'),
                hideMode: 'offsets',
                border: true,
                xtype: 'priceparser-grid-prices',
                layout: 'anchor',
                record: config.record.object,
                pageSize: 12
            }]
        }];
    },

    getTabProduct: function (config) {
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
            allowBlank: true,
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_sku'),
            name: 'sku',
            id: config.id + '-sku',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_product_category'),
            name: 'category',
            id: config.id + '-category',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_sb'),
            name: 'sb',
            id: config.id + '-sb',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_tc'),
            name: 'tc',
            id: config.id + '-tc',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_rrc'),
            name: 'rrc',
            id: config.id + '-rrc',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_margin'),
            name: 'margin',
            id: config.id + '-margin',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_minrent'),
            name: 'minrent',
            id: config.id + '-minrent',
            anchor: '99%',
            allowBlank: true,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('priceparser_product_minprice'),
            name: 'minprice',
            id: config.id + '-minprice',
            anchor: '99%',
            allowBlank: true,
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
Ext.reg('priceparser-product-window-update', priceParser.window.UpdateProduct);