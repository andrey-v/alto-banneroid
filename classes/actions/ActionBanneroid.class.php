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

class PluginAb_ActionBanneroid extends ActionPlugin {

    /**
     * Абстрактный метод инициализации экшена
     *
     */
    public function Init() {

    }

    /**
     * Регистрация ивентов
     *
     * @return void
     */
    protected function RegisterEvent() {
        $this->AddEvent('redirect', 'EventBannerRedirect');
    }


    /**
     * Перенаправление клика баннера с подсчетом суммы кликов
     *
     * @return bool
     */
    protected function EventBannerRedirect() {

        /** @var int $sBannerId Идентификатор баннера*/
        $sBannerId = $this->GetParam(0);

        $oBanner = $this->PluginAb_Banner_GetBannerById($sBannerId);
        if (!$oBanner) {
            return Router::Action('error');
        }

        $this->PluginAb_Banner_AddBannerStats(array
        ('banner_id' => $oBanner->getBannerId(),
            'event' => 'CLICK',
        ));

        Router::Location($oBanner->getBannerUrl());

        return true;
    }

}
