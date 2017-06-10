<?php

namespace Scaville\Lemon\Core\Database;

use Scaville\Lemon\Core\Database\DatabaseDriver;

class Connection {

    private static $driver;

    public function __construct() {
        
    }

    public static function init(DatabaseDriver $driver) {
        if (!isset(self::$driver)) {
            self::$driver = $driver;
        }
    }

    public static function getInstance() {
        return self::$driver;
    }

}
