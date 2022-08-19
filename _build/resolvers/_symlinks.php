<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/priceParser/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/priceparser')) {
            $cache->deleteTree(
                $dev . 'assets/components/priceparser/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/priceparser/', $dev . 'assets/components/priceparser');
        }
        if (!is_link($dev . 'core/components/priceparser')) {
            $cache->deleteTree(
                $dev . 'core/components/priceparser/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/priceparser/', $dev . 'core/components/priceparser');
        }
    }
}

return true;