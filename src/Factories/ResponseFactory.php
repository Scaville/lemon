<?php

namespace Scaville\Lemon\Factories;

use Scaville\Lemon\Core\MVC\View;
use Scaville\Lemon\Core\Interfaces\Factory;
use Scaville\Lemon\Core\HTTP\ViewResponse;

class ResponseFactory implements Factory {

    public static function factory($injector, array $params = array()) {
        if ($injector instanceof View) {
            return new ViewResponse($injector);
        } else {
            return $injector;
        }
    }

}
