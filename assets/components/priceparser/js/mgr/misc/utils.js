priceParser.utils.renderBoolean = function (value) {
    return value
        ? String.format('<span class="green">{0}</span>', _('yes'))
        : String.format('<span class="red">{0}</span>', _('no'));
};

priceParser.utils.getMenu = function (actions, grid, selected) {
    var menu = [];
    var cls, icon, title, action;

    var has_delete = false;
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue;
        }

        var a = actions[i];
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-');
            }
            continue;
        }
        else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
            menu.push('-');
            has_delete = true;
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue;
            }
            else if (typeof(a['multiple']) == 'string') {
                a['title'] = a['multiple'];
            }
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['menu']) != 'undefined') {
                icon += ' ' + a['cls']['menu'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        title = a['title'] ? a['title'] : a['title'];
        action = a['action'] ? grid[a['action']] : '';

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        });
    }

    return menu;
};

priceParser.utils.renderActions = function (value, props, row) {
    var res = [];
    var cls, icon, title, action, item;
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue;
        }
        var a = row.data.actions[i];
        if (!a['button']) {
            continue;
        }

        icon = a['icon'] ? a['icon'] : '';
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['button']) != 'undefined') {
                icon += ' ' + a['cls']['button'];
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : '';
        }
        action = a['action'] ? a['action'] : '';
        title = a['title'] ? a['title'] : '';

        item = String.format(
            '<li class="{0}"><button class="priceparser-btn priceparser-btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        );

        res.push(item);
    }

    return String.format(
        '<ul class="priceparser-row-actions">{0}</ul>',
        res.join('')
    );
};

priceParser.utils.formatDate = function (string) {
    if (string && string != '0000-00-00 00:00:00' && string != '-1-11-30 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string)
            ? new Date(string * 1000)
            : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));

        console.log(date)

        return date.strftime('%d.%m.%Y <span class="action-gray">%H:%M</span>');
    } else {
        return '&nbsp;';
    }
};

priceParser.utils.userLink = function(val,cell,row) {
    if (!val) {return '';}
    var action = MODx.action ? MODx.action['security/user/update'] : 'security/user/update';
    if (row.data['createdby']) {
        var id = row.data['createdby'];
    } else {
        var id = row.data['id'];
    }
    var url = 'index.php?a='+action+'&id='+id;
    //console.info(row);
    return '<a href="' + url + '" target="_blank">' + val + '</a>';
};