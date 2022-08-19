var priceParser = function (config) {
    config = config || {};
    priceParser.superclass.constructor.call(this, config);
};
Ext.extend(priceParser, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('priceparser', priceParser);

priceParser = new priceParser();