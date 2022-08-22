priceParser.window.CreateMarketplace = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-marketplace-window-create';
    }
    Ext.applyIf(config, {
        title: _('priceparser_marketplace_create'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/marketplace/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.CreateMarketplace.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.CreateMarketplace, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('priceparser_marketplace_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_marketplace_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_marketplace_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-marketplace-window-create', priceParser.window.CreateMarketplace);


priceParser.window.UpdateMarketplace = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-marketplace-window-update';
    }
    Ext.applyIf(config, {
        title: _('priceparser_marketplace_update'),
        width: 550,
        autoHeight: true,
        url: priceParser.config.connector_url,
        action: 'mgr/marketplace/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    priceParser.window.UpdateMarketplace.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.UpdateMarketplace, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('priceparser_marketplace_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('priceparser_marketplace_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('priceparser_marketplace_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('priceparser-marketplace-window-update', priceParser.window.UpdateMarketplace);