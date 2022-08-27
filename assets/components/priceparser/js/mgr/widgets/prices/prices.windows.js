priceParser.window.CreatePrice = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-price-window-create';
    }
    Ext.applyIf(config, {
        title: _('priceparser_price_create'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/price/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.CreatePrice.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.CreatePrice, MODx.Window, {

    getFields: function (config) {
        console.log(config.record)
        return [{
            xtype: 'textfield',
            fieldLabel: _('priceparser_price_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_price_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_price_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-price-window-create', priceParser.window.CreatePrice);


priceParser.window.UpdatePrice = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-price-window-update';
    }
    Ext.applyIf(config, {
        title: _('priceparser_price_update'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/price/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.UpdatePrice.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.UpdatePrice, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_price_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_price_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_price_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-price-window-update', priceParser.window.UpdatePrice);