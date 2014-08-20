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

class PluginAb_HookBanneroid extends Hook {

    /**
     * Register Hooks
     *
     * @return void
     */
    public function RegisterHook() {
        if (Router::GetAction() == 'admin') {
            return;
        }
        if (isset($_SERVER['REQUEST_URI'])) {
//            $this->AddHook('init_action', 'AddBannerBlock', __CLASS__, -100);
            $this->AddHook('template_layout_body_begin', 'AddBannerBlock', __CLASS__, -100);
            $this->AddHook(Config::Get('plugin.ab.banner_content_hook'), 'AddBannersInContent', __CLASS__, 0);
            $this->AddHook('template_layout_body_begin', 'AddBannersInHeader', __CLASS__, 0);
            $this->AddHook('template_layout_body_end', 'AddBannersInFooter', __CLASS__, 0);
        }
    }


    /**
     * Hook Handler
     * Add banners block to side bar
     *
     * @return mixed
     */
    public function AddBannerBlock() {

        if (in_array(Router::GetAction(), Config::Get('plugin.ab.banner_skip_actions'))) {
            return '';
        }
        $aBanners = $this->PluginAb_Banner_GetSideBarBanners($_SERVER['REQUEST_URI'], true);
        if (count($aBanners)) { //Insert banner block
            $this->Viewer_AddWidget('right', 'banneroid', array(
                'plugin' => 'ab',
                'aBanners' => $aBanners
            ), Config::Get('plugin.ab.banner_block_order'));
        }
        return '';
    }

    /**
     * Hook Handler
     * Add banners to content footer
     *
     * @return mixed
     */
    public function AddBannersInContent() {
        if (in_array(Router::GetAction(), Config::Get('plugin.ab.banner_skip_actions'))) {
            return false;
        }

        $aBanners = $this->PluginAb_Banner_GetContentBanners($_SERVER['REQUEST_URI'], true);
        if (count($aBanners)) { //Insert banner block
            $this->Viewer_Assign("aBanners", $aBanners);
            $this->Viewer_Assign('sBannersPath', Config::Get("plugin.ab.images_dir"));
            return $this->Viewer_Fetch(
                Plugin::GetTemplatePath(__CLASS__) . 'content.banneroid.tpl');
        }
    }

    /**
     * Hook Handler
     * Add banners to body header
     *
     * @return mixed
     */
    public function AddBannersInHeader() {
        if (in_array(Router::GetAction(), Config::Get('plugin.ab.banner_skip_actions'))) {
            return false;
        }

        $aBanners = $this->PluginAb_Banner_GetHeaderBanners($_SERVER['REQUEST_URI'], true);
        if (count($aBanners)) { //Insert banner block
            $this->Viewer_Assign("aBanners", $aBanners);
            $this->Viewer_Assign('sBannersPath', Config::Get("plugin.ab.images_dir"));
            return $this->Viewer_Fetch(
                Plugin::GetTemplatePath(__CLASS__) . 'header.banneroid.tpl');
        }
    }

    /**
     * Hook Handler
     * Add banners to body footer
     *
     * @return mixed
     */
    public function AddBannersInFooter($aVars) {
        if (in_array(Router::GetAction(), Config::Get('plugin.ab.banner_skip_actions'))) {
            return false;
        }

        $aBanners = $this->PluginAb_Banner_GetFooterBanners($_SERVER['REQUEST_URI'], true);
        if (count($aBanners)) { //Insert banner block
            $this->Viewer_Assign("aBanners", $aBanners);
            $this->Viewer_Assign('sBannersPath', Config::Get("plugin.ab.images_dir"));
            return $this->Viewer_Fetch(
                Plugin::GetTemplatePath(__CLASS__) . 'footer.banneroid.tpl');
        }
    }

}