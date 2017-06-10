<?php

namespace Scaville\Lemon\Core\Engine;

class ProviderLocator {

    private $providers = array();

    public function __get($name) {
        if (array_key_exists($name, $this->providers)) {
            return $this->providers[$name];
        }
        return null;
    }

    public function __set($name, $instance) {
        $this->providers[$name] = $instance;
        return $this;
    }
    
    public function getProviders(){
        return $this->providers;
    }
}
