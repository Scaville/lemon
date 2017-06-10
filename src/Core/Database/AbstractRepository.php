<?php

namespace Scaville\Lemon\Core\Database;

use Scaville\Lemon\Core\Application;

abstract class AbstractRepository {

    protected function read($sql, $params = null) {
        return Application::getConnection()->select($sql, $params);
    }
    
    protected function persistReturn($sql) {
        return Application::getConnection()->insert($sql);
    }

}
