priceParser.grid.Products = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-grid-products';
    }
    Ext.applyIf(config, {
        url: priceParser.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/product/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateProduct(grid, e, row);
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
    priceParser.grid.Products.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(priceParser.grid.Products, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = priceParser.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createProduct: function (btn, e) {
        var w = MODx.load({
            xtype: 'priceparser-product-window-create',
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

    updateProduct: function (btn, e, row) {
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
                action: 'mgr/product/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'priceparser-product-window-update',
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

    removeProduct: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('priceparser_products_remove')
                : _('priceparser_product_remove'),
            text: ids.length > 1
                ? _('priceparser_products_remove_confirm')
                : _('priceparser_product_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/product/remove',
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

    disableProduct: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/product/disable',
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

    enableProduct: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/product/enable',
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
        return [
            'id',
            'sku',
            'name',
            'category',
            'sb',
            'tc',
            'rrc',
            'margin',
            'minrent',
            'minprice',
            'ozcurprice',
            'oznewprice',
            'ymcurprice',
            'ymnewprice',
            'active',
            'actions'];
    },

    getColumns: function () {
        return [{
            header: _('priceparser_product_name'),
            dataIndex: 'name',
            sortable: true,
            width: 100,
        }, {
            header: _('priceparser_product_sku'),
            dataIndex: 'sku',
            sortable: false,
            width: 70,
        }, {
            header: _('priceparser_product_category'),
            dataIndex: 'category',
            sortable: true,
            width: 100,
        }, {
            header: _('priceparser_product_sb'),
            dataIndex: 'sb',
            sortable: true,
            width: 50,
        }, {
            header: _('priceparser_product_tc'),
            dataIndex: 'tc',
            sortable: true,
            width: 50,
        }, {
            header: _('priceparser_product_rrc'),
            dataIndex: 'rrc',
            sortable: true,
            width: 50,
        }, {
            header: _('priceparser_product_margin'),
            dataIndex: 'margin',
            sortable: true,
            width: 50,
        }, {
            header: _('priceparser_product_minrent'),
            dataIndex: 'minrent',
            sortable: true,
            width: 60,
        }, {
            header: _('priceparser_product_minprice'),
            dataIndex: 'minprice',
            sortable: true,
            width: 60,
        }, {
            header: _('priceparser_product_ozcurprice'),
            dataIndex: 'ozcurprice',
            sortable: true,
            width: 60,
        }, {
            header: _('priceparser_product_oznewprice'),
            dataIndex: 'oznewprice',
            sortable: true,
            width: 60,
        }, {
            header: _('priceparser_product_ymcurprice'),
            dataIndex: 'ymcurprice',
            sortable: true,
            width: 60,
        }, {
            header: _('priceparser_product_ymnewprice'),
            dataIndex: 'ymnewprice',
            sortable: true,
            width: 60,
        },/*{
            header: _('priceparser_product_active'),
            dataIndex: 'active',
            renderer: priceParser.utils.renderBoolean,
            sortable: true,
            width: 100,
        },*/ {
            header: _('priceparser_grid_actions'),
            dataIndex: 'actions',
            renderer: priceParser.utils.renderActions,
            sortable: false,
            width: 70,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('priceparser_product_create'),
            handler: this.createProduct,
            scope: this
        }, {
            text: '<i class="icon icon-csv"></i>&nbsp;' + _('priceparser_products_btn_import'),
            style: 'margin-left: 30px;',
            cls: 'primary-button',
            handler: this.importProducts,
            scope: this
        }, {
            text: '<i class="icon icon-csv"></i>&nbsp;' + _('priceparser_products_btn_export'),
            style: 'margin-left: 30px;',
            cls: 'primary-button',
            handler: this.exportProducts,
            scope: this
        }, {
            text: '<i class="icon icon-refresh"></i>&nbsp;' + _('priceparser_products_btn_refresh'),
            style: 'margin-left: 30px;',
            cls: 'primary-button',
            handler: this.refreshProducts,
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

    importProducts: function (btn, e) {
        console.log('import products')
        var w = MODx.load({
            xtype: 'priceparser-window-import',
            id: Ext.id(),
            baseParams: {
                action: 'mgr/product/import',
            },
            // Обновляем значение грида после импорта
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.show(e.target);
    },

    exportProducts: function () {
        console.log('export products')
    },

    refreshProducts: function () {
        console.log('refresh products')
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
Ext.reg('priceparser-grid-products', priceParser.grid.Products);



// Окно загрузки файла и импорта
priceParser.window.ImportProduct = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'priceparser-window-import';
    }
    Ext.applyIf(config, {
        title: _('priceparser_product_import_upload'),
        url: priceParser.config.connector_url,
        fileUpload: true,
        saveBtnText: _('priceparser_product_btn_import'),
        fields: this.getImportFields(config),
    });
    priceParser.window.ImportProduct.superclass.constructor.call(this, config);
};
Ext.extend(priceParser.window.ImportProduct, MODx.Window, {
    getImportFields: function (config) {
        return [{
            html: _('priceparser_product_import_msg'),
            id: config.id + '-desc',
            xtype: 'modx-description',
            style: 'margin-bottom: 5px; margin-top: 15px;'
        }, {
            xtype: 'fileuploadfield',
            fieldLabel: _('file'),
            buttonText: _('priceparser_product_import_upload'),
            name: 'file',
            id: config.id + '-file',
            anchor: '100%',
            //inputType: 'file'
        }]
    }
});
Ext.reg('priceparser-window-import', priceParser.window.ImportProduct);