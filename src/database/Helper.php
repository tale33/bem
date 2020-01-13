<?php

require_once ("Connection.php");

class Helper
{
    /**
     * @param $sql
     * @param array $params
     * @param bool $insert
     * @return array
     */
    public static function executeSQL($sql, $params = [], $insert = false) : array
    {
        $preparedStatement = self::getPreparedStatement($sql);
        try {
            $success = $preparedStatement->execute($params);
        } catch (PDOException $e) {
            echo [ 'error' => $e->getMessage() ];
            die();
        }
        Connection::getInstance()->closeConnection();
        if($insert) {
            return [ 'success' => $success ];
        } elseif (count($params) == 0) {
            return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $preparedStatement->fetch(PDO::FETCH_ASSOC);

        }
    }

    /**
     * @param $sql
     * @return PDOStatement
     */
    protected static function getPreparedStatement($sql) : PDOStatement
    {
        try {
            $connection = Connection::getInstance();
            $statement = $connection->getConnection()->prepare($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
        return $statement;
    }
}