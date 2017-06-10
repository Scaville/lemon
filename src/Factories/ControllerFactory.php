<?php

namespace Scaville\Lemon\Factories;

class ControllerFactory {

    private function __construct() {
        
    }

    public static function createInstance($route) {
        if (array_key_exists('class', $route->getController())) {
            $class = $route->getController()['class'];
            return new $class();
        } else {
            return null;
        }
    }

}
