<?php

/**
 * The home manager controller for priceParser.
 *
 */
class priceParserHomeManagerController extends modExtraManagerController
{
    /** @var priceParser $priceParser */
    public $priceParser;


    /**
     *
     */
    public function initialize()
    {
        $this->priceParser = $this->modx->getService('priceParser', 'priceParser', MODX_CORE_PATH . 'components/priceparser/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['priceparser:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('priceparser');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->priceParser->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/priceparser.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/widgets/products.grid.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/widgets/products.windows.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->priceParser->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        priceParser.config = ' . json_encode($this->priceParser->config) . ';
        priceParser.config.connector_url = "' . $this->priceParser->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "priceparser-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="priceparser-panel-home-div"></div>';

        return '';
    }
}