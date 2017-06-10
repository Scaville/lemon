<?php

namespace Scaville\Lemon\Factories;

use Scaville\Lemon\Core\Http\Mvc\View;
use Scaville\Lemon\Core\Interfaces\Factory;
use Scaville\Lemon\Core\Http\ViewResponse;

class ResponseFactory implements Factory {

    public static function factory($injector, array $params = array()) {
        if ($injector instanceof View) {
            return new ViewResponse($injector);
        } else {
            return $injector;
        }
    }

}
