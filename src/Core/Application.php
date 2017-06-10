<?php

namespace Scaville\Lemon\Core;

use Scaville\Lemon\Core\Engine\ProviderLocator;
use Scaville\Lemon\Core\Engine\ServiceLocator;
use Scaville\Lemon\Core\Engine\SettingsEngine;
use Scaville\Lemon\Providers\Http;
use Scaville\Lemon\Providers\Settings;

abstract class Application {

    private static $connection;
    private static $providerLocator;
    private static $serviceLocator;

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

    /**
     * Initialize the application.
     */
    public static function initApplication() {
        SettingsEngine::singleton();
        self::$providerLocator = new ProviderLocator();
        self::$serviceLocator = new ServiceLocator();
        self::loadProviders();
        self::loadServices();
        self::createConnection();
        self::getProvider(Http::class)->openRoute();
    }

    /**
     * Load the application and module providers to ProviderLocator.
     */
    private static function loadProviders() {
        $providers = SettingsEngine::getSettings()['module'];
        foreach ($providers as $module => $configs) {
            if (array_key_exists('providers', $configs)) {
                foreach ($configs['providers'] as $name => $class) {
                    self::$providerLocator->$class = new $class();
                }
            }
        }
        unset($providers);
    }

    /**
     * Load the application and module services to ServiceLocator.
     */
    private static function loadServices() {
        $services = SettingsEngine::getSettings()['module'];
        foreach ($services as $module => $configs) {
            if (array_key_exists('services', $configs)) {
                foreach ($configs['services'] as $name => $class) {
                    self::$serviceLocator->$class['service'] = new $class['service'](new $class['repository']());
                }
            }
        }
        unset($services);
    }

    /**
     * Return a provider.
     * @param string $name
     * @return Scavile\Lemon\Core\Interfaces\Provider
     */
    public static function getProvider($name) {
        return self::$providerLocator->$name;
    }

    /**
     * Return a provider.
     * @return array
     */
    public static function getProviders() {
        return self::$providerLocator->getProviders();
    }

    /**
     * Return the Service Locator Instance
     * @return Scaville\Lemon\Core\Engine\ServiceLocator
     */
    public static function getServiceLocator() {
        return self::$serviceLocator;
    }

    /**
     * Return the Provider Locator Instance
     * @return Scaville\Lemon\Core\Engine\ProviderLocator
     */
    public function getProviderLocator() {
        return self::$providerLocator;
    }

    /**
     * Create and initialize the database connection.
     */
    public static function createConnection() {
        $databases = self::getProvider(Settings::class)->getApplicationSetting('database');

        foreach ($databases as $name => $stack) {
            if ($stack['current'] === true) {
                Database\Connection::init($stack['driver']);
                self::$connection = Database\Connection::getInstance();
            }
        }
    }
    
    /**
     * Returns the database connection.
     * @return Scaville\Lemon\Core\Database\Conneciton
     */
    public static function getConnection(){
        return self::$connection;
    }

}
