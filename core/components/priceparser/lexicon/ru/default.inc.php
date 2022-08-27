<?php
include_once 'setting.inc.php';

$_lang['priceparser'] = 'Цены конкурентов';
$_lang['priceparser_menu_desc'] = 'Парсинг цен конкурентов с маркетплейсов';
$_lang['priceparser_products_intro_msg'] = 'На этой странице можно добавлять, редактировать, удалять, импортировать 
и экспортировать товары для которых собирается ценовая статистика';

$_lang['priceparser_grid_search'] = 'Поиск';
$_lang['priceparser_grid_actions'] = 'Действия';

$_lang['priceparser_products'] = 'Товары';
$_lang['priceparser_product'] = 'Товар';
$_lang['priceparser_product_prices'] = 'Цены и ссылки';
$_lang['priceparser_product_id'] = 'Id';
$_lang['priceparser_product_name'] = 'Название';
$_lang['priceparser_product_sku'] = 'Артикул';
$_lang['priceparser_product_category'] = 'Категория';
$_lang['priceparser_product_active'] = 'Активный';

$_lang['priceparser_product_sb'] = 'CБ';
$_lang['priceparser_product_tc'] = 'ТЦ';
$_lang['priceparser_product_rrc'] = 'РРЦ';
$_lang['priceparser_product_margin'] = 'Маржа';
$_lang['priceparser_product_minrent'] = 'Мин. рент.';
$_lang['priceparser_product_minprice'] = 'Мин. цена';

$_lang['priceparser_product_ozcurprice'] = 'Наша OZON';
$_lang['priceparser_product_oznewprice'] = 'Новая OZON';
$_lang['priceparser_product_ymcurprice'] = 'Наша ЯМ';
$_lang['priceparser_product_ymnewprice'] = 'Новая ЯМ';

$_lang['priceparser_product_create'] = 'Добавить товар';
$_lang['priceparser_product_update'] = 'Изменить товар';
$_lang['priceparser_product_enable'] = 'Включить товар';
$_lang['priceparser_products_enable'] = 'Включить товары';
$_lang['priceparser_product_disable'] = 'Отключить товар';
$_lang['priceparser_products_disable'] = 'Отключить товары';
$_lang['priceparser_product_remove'] = 'Удалить товар';
$_lang['priceparser_products_remove'] = 'Удалить товары';
$_lang['priceparser_product_remove_confirm'] = 'Вы уверены, что хотите удалить этот товар?';
$_lang['priceparser_products_remove_confirm'] = 'Вы уверены, что хотите удалить эти товары?';

$_lang['priceparser_product_err_name'] = 'Вы должны указать название товара';
$_lang['priceparser_product_err_sku'] = 'Вы должны указать артикул товара';
$_lang['priceparser_product_err_ae'] = 'Товар с таким названием уже существует';
$_lang['priceparser_product_err_nf'] = 'Товар не найден';
$_lang['priceparser_product_err_ns'] = 'Товар не указан';
$_lang['priceparser_product_err_remove'] = 'Ошибка при удалении товара';
$_lang['priceparser_product_err_save'] = 'Ошибка при сохранении товара';

$_lang['priceparser_products_btn_import'] = 'Импорт товаров';
$_lang['priceparser_products_btn_export'] = 'Экспорт товаров и цен';
$_lang['priceparser_products_btn_refresh'] = 'Обновить цены';

$_lang['priceparser_button_import'] = 'Импортировать товары';
$_lang['priceparser_product_import_upload'] = 'Загрузить файл';
$_lang['priceparser_product_btn_import'] = 'Импортировать товары';
$_lang['priceparser_product_import_msg'] = 'Загрузите файл с данными в формате .xlsx';



$_lang['priceparser_marketplaces'] = 'Маркетплейсы';
$_lang['priceparser_marketplaces_intro_msg'] = 'На этой странице можно добавлять, редактировать, удалять маркетплейсы';
$_lang['priceparser_marketplace_id'] = 'Id';
$_lang['priceparser_marketplace_name'] = 'Название';
$_lang['priceparser_marketplace_description'] = 'Описание';
$_lang['priceparser_marketplace_active'] = 'Активный';

$_lang['priceparser_marketplace_create'] = 'Добавить маркетплейс';
$_lang['priceparser_marketplace_update'] = 'Изменить маркетплейс';
$_lang['priceparser_marketplace_enable'] = 'Включить маркетплейс';
$_lang['priceparser_marketplaces_enable'] = 'Включить маркетплейсы';
$_lang['priceparser_marketplace_disable'] = 'Отключить маркетплейс';
$_lang['priceparser_marketplaces_disable'] = 'Отключить маркетплейсы';
$_lang['priceparser_marketplace_remove'] = 'Удалить маркетплейс';
$_lang['priceparser_marketplaces_remove'] = 'Удалить маркетплейсы';
$_lang['priceparser_marketplace_remove_confirm'] = 'Вы уверены, что хотите удалить этот маркетплейс?';
$_lang['priceparser_marketplaces_remove_confirm'] = 'Вы уверены, что хотите удалить эти маркетплейсы?';

$_lang['priceparser_marketplace_err_name'] = 'Вы должны указать имя маркетплейса';
$_lang['priceparser_marketplace_err_ae'] = 'Маркетплейс с таким именем уже существует';
$_lang['priceparser_marketplace_err_nf'] = 'Маркетплейс не найден';
$_lang['priceparser_marketplace_err_ns'] = 'Маркетплейс не указан';
$_lang['priceparser_marketplace_err_remove'] = 'Ошибка при удалении маркетплейса';
$_lang['priceparser_marketplace_err_save'] = 'Ошибка при сохранении маркетплейса';


$_lang['priceparser_prices'] = 'Цены';
$_lang['priceparser_prices_intro_msg'] = 'На этой вкладке можно добавлять, редактировать, удалять ссылки на 
страницы конкурентов, которые сразу после сохранения будут пропарсены и обновлены цены';
$_lang['priceparser_price_id'] = 'Id';
$_lang['priceparser_price_name'] = 'Название';
$_lang['priceparser_price_link'] = 'Ссылка';
$_lang['priceparser_price_marketplace'] = 'Маркетплейс';
$_lang['priceparser_price_fullname'] = 'Автор';
$_lang['priceparser_price_price'] = 'Цена';
$_lang['priceparser_price_createdon'] = 'Создана';
$_lang['priceparser_price_updatedon'] = 'Обновлена';
$_lang['priceparser_price_active'] = 'Активная';

$_lang['priceparser_price_create'] = 'Добавить';
$_lang['priceparser_price_update'] = 'Изменить Предмет';
$_lang['priceparser_price_enable'] = 'Включить Предмет';
$_lang['priceparser_prices_enable'] = 'Включить Предметы';
$_lang['priceparser_price_disable'] = 'Отключить Предмет';
$_lang['priceparser_prices_disable'] = 'Отключить Предметы';
$_lang['priceparser_price_remove'] = 'Удалить Предмет';
$_lang['priceparser_prices_remove'] = 'Удалить Предметы';
$_lang['priceparser_price_remove_confirm'] = 'Вы уверены, что хотите удалить этот Предмет?';
$_lang['priceparser_prices_remove_confirm'] = 'Вы уверены, что хотите удалить эти Предметы?';

$_lang['priceparser_price_err_name'] = 'Вы должны указать имя Предмета.';
$_lang['priceparser_price_err_ae'] = 'Предмет с таким именем уже существует.';
$_lang['priceparser_price_err_nf'] = 'Предмет не найден.';
$_lang['priceparser_price_err_ns'] = 'Предмет не указан.';
$_lang['priceparser_price_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['priceparser_price_err_save'] = 'Ошибка при сохранении Предмета.';







$_lang['priceparser_items'] = 'Предметы';
$_lang['priceparser_item_id'] = 'Id';
$_lang['priceparser_item_name'] = 'Название';
$_lang['priceparser_item_description'] = 'Описание';
$_lang['priceparser_item_active'] = 'Активно';

$_lang['priceparser_item_create'] = 'Создать предмет';
$_lang['priceparser_item_update'] = 'Изменить Предмет';
$_lang['priceparser_item_enable'] = 'Включить Предмет';
$_lang['priceparser_items_enable'] = 'Включить Предметы';
$_lang['priceparser_item_disable'] = 'Отключить Предмет';
$_lang['priceparser_items_disable'] = 'Отключить Предметы';
$_lang['priceparser_item_remove'] = 'Удалить Предмет';
$_lang['priceparser_items_remove'] = 'Удалить Предметы';
$_lang['priceparser_item_remove_confirm'] = 'Вы уверены, что хотите удалить этот Предмет?';
$_lang['priceparser_items_remove_confirm'] = 'Вы уверены, что хотите удалить эти Предметы?';

$_lang['priceparser_item_err_name'] = 'Вы должны указать имя Предмета.';
$_lang['priceparser_item_err_ae'] = 'Предмет с таким именем уже существует.';
$_lang['priceparser_item_err_nf'] = 'Предмет не найден.';
$_lang['priceparser_item_err_ns'] = 'Предмет не указан.';
$_lang['priceparser_item_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['priceparser_item_err_save'] = 'Ошибка при сохранении Предмета.';