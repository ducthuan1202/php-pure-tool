<?php

namespace Src;

class Database
{
    private static $conn;

    private static function init()
    {
        $database = config('database');
        $host = arr_get($database, 'host', 'localhost');
        $port = arr_get($database, 'port', 3306);
        $name = arr_get($database, 'dbname', '');
        $user = arr_get($database, 'username', '');
        $pass = arr_get($database, 'password', '');
        $charset = arr_get($database, 'charset', '');

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s;port=%d', $host, $name, $charset, $port);
        self::$conn = new \PDO($dsn, $user, $pass);
        self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function getConnect()
    {
        if (!self::$conn) {
            self::init();
        }
        return self::$conn;
    }

    public static function findAll($query, $bindParams = [])
    {
        $stmt = self::query($query, $bindParams);
        return $stmt->fetchAll();
    }

    public static function findOne($query, $bindParams = [])
    {
        $stmt = self::query($query, $bindParams);
        return $stmt->fetch();
    }

    public static function insert($sql, $bindParams = [])
    {
        $stmt = self::execute($sql, $bindParams);
        return $stmt->lastInsertId();
    }

    public static function update($sql, $bindParams = [])
    {
        $stmt = self::execute($sql, $bindParams);
        return $stmt->rowCount();
    }

    public static function delete($sql, $bindParams = [])
    {
        $stmt = Database::getConnect()->prepare($sql);
        self::bindParams($stmt, $bindParams);
        return self::execute($sql, $bindParams);
    }

    public static function transaction(callable $fn)
    {
        $conn = Database::getConnect();

        $conn->beginTransaction();
        try {
            $result = call_user_func($fn);
            $conn->commit();
            return $result;
        } catch (\Exception $exception) {
            $conn->rollBack();
            throw $exception;
        }
    }

    private static function query($sql, $bindParams = [])
    {
        $stmt = self::execute($sql, $bindParams);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        return $stmt;
    }

    private static function execute($sql, $bindParams = [])
    {
        $stmt = Database::getConnect()->prepare($sql);
        self::bindParams($stmt, $bindParams);
        $stmt->execute();
        return $stmt;
    }

    private static function bindParams($stmt, $params)
    {
        if (empty($params)) {
            return;
        }

        foreach ($params as $key => $value) :
            if (is_numeric($value)) {
                $stmt->bindParam($key, $params[$key], \PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $params[$key], \PDO::PARAM_STR);
            }
        endforeach;
    }
}
