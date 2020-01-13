<?php

require_once ("Singleton.php");
require_once ("ConfigurationSettings.php");

class Configuration extends Singleton
{
    private $host;
    private $dbName;
    private $username;
    private $password;

    /**
     * Configuration constructor
     */
    public function __construct() {
        $this->host = DB_HOST;
        $this->dbName = DB_NAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
    }

    /**
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getDBName() : string
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }
}