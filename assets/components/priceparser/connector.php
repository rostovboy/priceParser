<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var priceParser $priceParser */
$priceParser = $modx->getService('priceParser', 'priceParser', MODX_CORE_PATH . 'components/priceparser/model/');
$modx->lexicon->load('priceparser:default');

// handle request
$corePath = $modx->getOption('priceparser_core_path', null, $modx->getOption('core_path') . 'components/priceparser/');
$path = $modx->getOption('processorsPath', $priceParser->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);