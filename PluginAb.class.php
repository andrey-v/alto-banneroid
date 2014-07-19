<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Banneroid
 * @Plugin Id: Banneroid
 * @Plugin URI:
 * @Description: Banner rotator for LS
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */


/**
 * Deny direct access to this file
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

/**
 * Banneroid Plugin class for LiveStreet
 */
class PluginAb extends Plugin {

    /** @var array $aInherits Объявление переопределений (модули, мапперы и сущности) */
    protected $aInherits = array(
        'actions' => array(
            'ActionAdmin',
        ),

    );

    /**
     * Plugin Activation
     * Creating tables in DB if not exists
     * @return boolean
     */
    public function Activate() {
        // Export sql dump
        $this->ExportSQL(dirname(__FILE__) . '/activate.sql');
        // create image directory if it is not exists
        $sDir = Config::Get('path.root.server') . Config::Get('path.uploads.root') . '/banneroid/';
        if (!file_exists($sDir)) {
            @mkdir($sDir);
            @chmod($sDir, 0777);
        }
        $this->Cache_Clean();
        return true;
    }

    /**
     * Plugin Initialization
     *
     * @return void
     */
    public function Init() {

        $this->Viewer_AppendStyle(Plugin::GetTemplateDir(__CLASS__) . 'assets/css/style.css');
        $this->Viewer_AppendScript(Plugin::GetTemplateDir(__CLASS__) . 'assets/js/script.js');
    }

    /**
     * Деактивация плагина
     *
     * @return boolean
     */
    public function Deactivate() {
        $this->Cache_Clean();
        $resutls = $this->ExportSQL(dirname(__FILE__) . '/deactivate.sql');
        return $resutls['result'];
    }

}
