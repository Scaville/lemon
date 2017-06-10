<?php

namespace Scaville\Lemon\Core\Interfaces;

interface Factory {

    public static function factory($injector, array $params = array());
}
