<?php

namespace Scaville\Lemon\Core\Database;

interface DatabaseDriver {

    public function connect();

    public function disconnect();

    public function select($sql, array $params = null);

    public function insert($sql);

    public function update($sql, array $params = null);

    public function delete($sql, array $params = null);

    public function prepare($sql, array $params = null);

    public function execute($sql, array $params = null);

    public function setAutocommit($param);

    public function setKeep($param);
}
