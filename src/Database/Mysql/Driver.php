<?php

namespace Scaville\Lemon\Database\Mysql;

use Scaville\Lemon\Core\Database\DatabaseDriver;
use Scaville\Lemon\Core\Exceptions\DatabaseException;
use Scaville\Lemon\Constants\Messages;

class Driver implements DatabaseDriver {

    private $keep = false;
    private $autocommit = true;
    private $driver;
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    private $socket;

    /**
     * Constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $port
     * @param string $socket
     */
    public function __construct($host = null, $username = null, $password = null, $database = null, $port = null, $socket = null) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
        $this->socket = $socket;
        $this->driver = new \mysqli();
    }

    /**
     * Open database connection;
     * @return \Lemon\database\drivers\mysql
     * @throws DatabaseException
     */
    public function connect() {
        if (
                null === $this->host ||
                null === $this->username ||
                null === $this->password ||
                null === $this->database) {
            throw new DatabaseException(Messages::DB_CONNECTION_PARAMS_NULL);
        }

        try {
            $this->driver->connect($this->host, $this->username, $this->password, $this->database, $this->port, $this->socket);
            mysqli_set_charset($this->driver, "utf8");
        } catch (Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * Close the connection.
     * @return DatabaseDriver
     */
    public function disconnect() {
        $this->driver->close();

        return $this;
    }

    /**
     * Execute a delete script.
     * @param \string $sql
     * @return type
     * @throws DatabaseException
     */
    public function select($sql, array $params = null) {
        $this->connect();

        if (false === strpos(strtoupper($sql), 'SELECT')) {
            throw new DatabaseException(Messages::DB_QUERY_SELECT_RESTRICT);
        }

        if (null !== $params) {
            $this->prepare($sql, $params)->execute();
            $query = $this->prepare($sql, $params);
            $query->execute();
            return $query->get_result()
                            ->fetch_all(MYSQLI_ASSOC);
        } else {
            $query = $this->driver->query($sql);
        }

        $result = array();

        if(false === $query){
            return array();
        }
        
        while ($line = $query->fetch_array(MYSQLI_ASSOC)) {
            $result[] = $line;
        }

        $this->disconnect();

        return $result;
    }

    /**
     * Execute a delete script.
     * @param \string $sql
     * @return type
     * @throws DatabaseException
     */
    public function delete($sql, array $params = null) {
        $this->connect();

        if (!strpos(strtoupper($sql), 'DELETE')) {
            throw new DatabaseException(Messages::DB_QUERY_DELETE_RESTRICT);
        }

        $this->disconnect();

        return $this->driver->query($sql);
    }

    /**
     * Excecuta uma query.
     * @param \string $sql
     * @param array $params
     * @return \Lemon\database\drivers\mysql
     */
    public function execute($sql, array $params = null) {
        $this->connect();

        if (null !== $params) {
            $this->prepare($sql, $params)
                    ->execute();
        } else {
            $this->driver->query($sql);
        }

        $this->disconnect();

        return $this;
    }

    /**
     * Prepara uma query.
     * @param string $sql
     * @param array $params
     * @return \mysqli_stmt
     */
    public function prepare($sql, array $params = null) {
        $this->connect();

        $statement = $this->driver->prepare($sql);

        foreach ($params as $index => $value) {
            $type = "";

            switch ($value) {
                case is_string($value):
                    $type = "s";
                    break;
                case is_int($value):
                    $type = "i";
                    break;
                case is_double($value):
                    $type = "d";
                    break;
                default :
                    $type = "b";
                    break;
            }

            $statement->bind_param($type, $value);
        }

        return $statement;
    }

    public function insert($sql) {
        $this->connect();

        $this->driver->query($sql);

        $id = $this->driver->insert_id;

        $this->disconnect();
        
        return $id;
    }

    public function update($sql, array $params = null) {
        
    }

    public function getHost() {
        return $this->host;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setDatabase($database) {
        $this->database = $database;
        return $this;
    }

    public function setAutocommit($param) {
        $this->autocommit = $param;
    }

    public function setKeep($param) {
        $this->keep = $param;
    }

}
