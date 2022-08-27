priceParser.grid.Prices = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-grid-prices';
    }
    Ext.applyIf(config, {
        url: priceParser.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/price/getlist',
            product_id: config.record.id
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updatePrice(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'priceparser-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    priceParser.grid.Prices.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(priceParser.grid.Prices, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = priceParser.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createPrice: function (btn, e) {
        var w = MODx.load({
            xtype: 'priceparser-price-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({active: true});
        w.show(e.target);
    },

    updatePrice: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/price/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'priceparser-price-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removePrice: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('priceparser_prices_remove')
                : _('priceparser_price_remove'),
            text: ids.length > 1
                ? _('priceparser_prices_remove_confirm')
                : _('priceparser_price_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/price/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    disablePrice: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/price/disable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    enablePrice: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/price/enable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    getFields: function () {
        return ['id', 'name', 'product_id', 'link', 'marketplace_id', 'marketplace', 'price', 'createdon', 'updatedon', 'createdby', 'fullname', 'active', 'actions'];
    },

    getColumns: function () {
        return [/*{
            header: _('priceparser_price_id'),
            dataIndex: 'id',
            sortable: true,
            width: 50
        },*/ {
            header: _('priceparser_price_name'),
            dataIndex: 'name',
            sortable: true,
            width: 120,
        }, {
            header: _('priceparser_price_marketplace'),
            dataIndex: 'marketplace_id',
            sortable: true,
            width: 100,
        }, {
            header: _('priceparser_price_link'),
            dataIndex: 'link',
            sortable: false,
            width: 100,
        }, {
            header: _('priceparser_price_price'),
            dataIndex: 'price',
            sortable: false,
            width: 70,
        }, /*{
            header: _('priceparser_price_createdby'),
            dataIndex: 'fullname',
            renderer: priceParser.utils.userLink,
            sortable: true,
            width: 100,
        },*/ {
            header: _('priceparser_price_createdon'),
            dataIndex: 'createdon',
            //renderer: priceParser.utils.formatDate,
            sortable: true,
            width: 120,
        }, {
            header: _('priceparser_price_updatedon'),
            dataIndex: 'updatedon',
            //renderer: priceParser.utils.formatDate,
            sortable: true,
            width: 120,
        }, {
            header: _('priceparser_price_active'),
            dataIndex: 'active',
            renderer: priceParser.utils.renderBoolean,
            sortable: true,
            width: 70,
        }, {
            header: _('priceparser_grid_actions'),
            dataIndex: 'actions',
            renderer: priceParser.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('priceparser_price_create'),
            handler: this.createPrice,
            scope: this
        }, '->', {
            xtype: 'priceparser-field-search',
            width: 250,
            listeners: {
                search: {
                    fn: function (field) {
                        this._doSearch(field);
                    }, scope: this
                },
                clear: {
                    fn: function (field) {
                        field.setValue('');
                        this._clearSearch();
                    }, scope: this
                },
            }
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('priceparser-grid-prices', priceParser.grid.Prices);
