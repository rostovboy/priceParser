priceParser.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        cls: 'container',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'priceparser-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('priceparser') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('priceparser_items'),
                layout: 'anchor',
                items: [{
                    html: _('priceparser_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'priceparser-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    priceParser.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.panel.Home, MODx.Panel);
Ext.reg('priceparser-panel-home', priceParser.panel.Home);
