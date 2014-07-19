<?php

/**
 * HookAdmin.class.php
 * Файл хука плагина ab
 *
 * @author      Андрей Г. Воронов <andreyv@gladcode.ru>
 * @copyrights  Copyright © 2014, Андрей Г. Воронов
 *              Является частью плагина ab
 * @version     0.0.1 от 19.07.2014 09:17
 */
class PluginAb_HookAdmin extends Hook {

    /**
     * Регистрация хуков
     *
     * @return void
     */
    public function RegisterHook() {
        $this->AddHook('template_admin_menu_content', 'AdminMenuInject', __CLASS__);

    }

    /**
     * Доабвление ссылки в меню админки
     *
     * @return string
     */
    public function AdminMenuInject() {
        if (E::IsAdmin()) {
            return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'menu.banneroid.tpl');
        }
    }

}