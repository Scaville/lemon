<?php

namespace Scaville\Lemon\Core\Engine;

class ServiceLocator {

    private $services = array();

    public function __get($name) {
        return $this->get($name);
    }

    public function __set($name, $instance) {
        $this->services[$name] = $instance;
        return $this;
    }

    public function get($name){
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        }
        return null;
    }
}
