<?php

require_once ("Configuration.php");

class Connection extends Singleton
{
    private $connection;
    private $connectionOpen = false;

    /**
     * Connect to database
     * @return PDO
     */
    public function getConnection() : PDO
    {
        if(!$this->connectionOpen) {
            $config = Configuration::getInstance();
            try {
                $this->connection = new PDO(
                    'mysql:host=' . $config->getHost() . ';dbname=' . $config->getDBName() . ';charset=utf8mb4',
                    $config->getUsername(),
                    $config->getPassword()
                );
            } catch (Exception $e) {
                die('Connection to MYSQL at ' . $config->getHost() . ' failed!');
            }
            if(!$this->connection) {
                die('Connection to MYSQL at ' . $config->getHost() . ' failed!');
            }
            $this->connectionOpen = true;
        }
        return $this->connection;
    }

    /**
     * @return void
     */
    public function closeConnection() : void
    {
        if(!$this->connectionOpen) return;
        $this->connection = null;
        $this->connectionOpen = false;
    }
}