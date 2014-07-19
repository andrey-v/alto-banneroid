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
 * ActionAdmin.class.php
 * Файл экшена плагина ab
 *
 * @author      Андрей Г. Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Г. Воронов
 *              Является частью плагина ab
 * @version     0.0.1 от 19.07.2014 09:17
 */
class PluginAb_ActionAdmin extends PluginAb_Inherit_ActionAdmin {

    /**
     * Регистрация экшенов админки
     */
    protected function RegisterEvent() {

        parent::RegisterEvent();

        $this->AddEvent('banneroid-list', 'EventAdminBanneroidList');
        $this->AddEvent('banneroid-add', 'EventAdminBanneroidAdd');
        $this->AddEvent('banneroid-edit', 'EventAdminBanneroidEdit');
        $this->AddEvent('banneroid-delete', 'EventAdminBanneroidDelete');
        $this->AddEvent('banneroid-stats', 'EventAdminBanneroidStatistic');
        $this->AddEvent('banneroid-stats-banners', 'EventAdminBanneroidStatisticBanners');
    }

    /**
     * Статитика по баннерам
     * @return bool
     */
    protected function EventAdminBanneroidStatisticBanners() {
        if ($sBannerId = (int)$this->GetParam(0)) {
            $oBanner = $this->PluginAb_Banner_GetBannerById($sBannerId);
            if (!$oBanner) {
                return Router::Action('error');
            }
            $this->Viewer_Assign('oBanner', $oBanner);
            $this->Viewer_Assign('aBannersStats', $this->PluginAb_Banner_GetStatsBanners($sBannerId));
        } else {
            $this->Viewer_Assign('aBannersStats', $this->PluginAb_Banner_GetStatsBanners());
        }

        return false;
    }

    /**
     * Общая статистика
     */
    protected function EventAdminBanneroidStatistic() {
        $this->Viewer_Assign('aBannersStats', $this->PluginAb_Banner_GetStatsTotal());
    }

    /**
     * Список баннеров
     */
    protected function EventAdminBanneroidList() {
        $this->sMainMenuItem = 'content';

        $this->Viewer_Assign('aBannersList', $this->PluginAb_Banner_GetBannersList());
        $this->_setTitle($this->Lang_Get('plugin.ab.banneroid_title'));


    }

    /**
     * Добавление баннера
     */
    protected function EventAdminBanneroidAdd() {
        $this->sMainMenuItem = 'content';

        $oBanner = new PluginAb_ModuleBanner_EntityBanner();
        $oBanner->setBannerStartDate();
        $oBanner->setBannerId(0);
        $this->Viewer_Assign('add_banner', 1);

        if (getRequest('submit_banner')) {
            if ($this->PluginAb_Banner_Save($oBanner)) {
                $this->Message_AddNotice($this->Lang_Get('plugin.ab.banneroid_ok_add'), $this->Lang_Get('attention'), true);
                Router::Location(Config::Get("path.root.web") . 'admin/banneroid-list/');
            }
        }


        $this->Viewer_Assign('oBanner', $oBanner);
        $_REQUEST['banner_places'] = $this->PluginAb_Banner_GetAllPages();
        $_REQUEST['banner_start_date'] = date('Y-m-d');
        $_REQUEST['banner_end_date'] = '0000-00-00';
        $_REQUEST['banner_is_image'] = true;
        $_REQUEST['banner_type'] = 1;


        $this->_setTitle($this->Lang_Get('plugin.ab.banneroid_edit'));
    }

    /**
     * Редактирование баннера
     */
    protected function EventAdminBanneroidEdit() {
        $this->sMainMenuItem = 'content';

        $sBannerId = (int)$this->GetParam(0); // Id of current banner

        $oBanner = $this->PluginAb_Banner_GetBannerById($sBannerId);

        if (!$oBanner) {
            return Router::Action('error');
        }

        if (getRequest('submit_banner')) {
            if ($this->PluginAb_Banner_Save($oBanner)) {
                $this->Message_AddNotice($this->Lang_Get('plugin.ab.banneroid_ok_edit'), $this->Lang_Get('attention'), true);
                Router::Location(Config::Get("path.root.web") . 'admin/banneroid-list/');
            }
        }


        $this->Viewer_Assign('oBanner', $oBanner);
        $this->Viewer_Assign('aPages', $this->PluginAb_Banner_GetActivePages($oBanner));

        $_REQUEST['banner_name'] = $oBanner->getBannerName();
        $_REQUEST['banner_html'] = $oBanner->getBannerHtml();
        $_REQUEST['banner_url'] = $oBanner->getBannerUrl();
        $_REQUEST['banner_lang'] = $oBanner->getBannerLang();
        $_REQUEST['banner_start_date'] = $oBanner->getBannerStartDate();
        $_REQUEST['banner_end_date'] = $oBanner->getBannerEndDate();
        $_REQUEST['banner_is_active'] = $oBanner->getBannerIsActive();
        $_REQUEST['banner_places'] = $this->PluginAb_Banner_GetAllPages();
        $_REQUEST['banner_type'] = $oBanner->getBannerType();

        if (strlen(@$oBanner->getBannerImage())) {
            $_REQUEST['banner_image'] = Config::Get("plugin.ab.images_dir") .
                $oBanner->getBannerImage();
            $_REQUEST['banner_is_image'] = true;
        }

        $this->_setTitle($this->Lang_Get('plugin.ab.banneroid_edit'));
        $this->SetTemplateAction('banneroid-add');
    }

    /**
     * Удаление баннера
     */
    protected function EventAdminBanneroidDelete() {
        $sBannerId = $this->GetParam(0);

        $this->PluginAb_Banner_HideBanner($sBannerId);
        $this->Message_AddNotice($this->Lang_Get('plugin.ab.banneroid_ok_delete'), $this->Lang_Get('attention'), true);

        Router::Location(Config::Get("path.root.web") . 'admin/banneroid-list/');
    }

}