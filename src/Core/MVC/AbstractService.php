<?php

namespace Scaville\Lemon\Core\MVC;

abstract class AbstractService {

    protected $repository;

    public function __call($name, $params) {
        return $this->repository->$name($params);
    }

    public function __construct($repository) {
        $this->repository = new $repository();
    }

}
