priceParser.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'priceparser-panel-home',
            renderTo: 'priceparser-panel-home-div'
        }]
    });
    priceParser.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.page.Home, MODx.Component);
Ext.reg('priceparser-page-home', priceParser.page.Home);