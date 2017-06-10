<?php

namespace Scaville\Lemon\Providers;

use Scaville\Lemon\Core\Interfaces\Provider;
use Scaville\Lemon\Core\Engine\SettingsEngine;
use Scaville\Lemon\Core\Application;

class Settings implements Provider {

    public function __construct() {
        
    }

    /**
     * Returns a setting global of the application.
     * @param string $setting
     * @return variant
     */
    public function getApplicationSetting($setting) {
        $applicationSettings = SettingsEngine::getSettings()['application'];

        if (array_key_exists($setting, $applicationSettings)) {
            return $applicationSettings[$setting];
        }
        unset($applicationSettings);
    }

    /**
     * Returns a setting of the a module of the application.
     * @param string $setting
     * @param string $module
     * @return variant
     */
    public function getModuleSetting($setting, $module = null) {
        if (null === $module) {
            $module = strtolower(Application::getProvider(Http::class)->getRequest()->getRoute()->getModule());
        } else {
            $module = strtolower($module);
        }
        
        $moduleSettings = array_change_key_case(SettingsEngine::getSettings()['module'], CASE_LOWER);
        $moduleSettings = $moduleSettings[$module];

        if (array_key_exists($setting, $moduleSettings)) {
            return $moduleSettings[$setting];
        }
        unset($moduleSettings);
    }

}
