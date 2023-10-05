<?php

namespace app\services;

use PDO;

class DB
{
    private static function getInstance()
    {
        static $connect;
        return $connect ?: $connect = self::createPDO(self::getConfig());
    }

    private static function createPDO(array $config): PDO
    {
        return new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
    }

    private static function getConfig(): array
    {
        return include 'config.php';
    }

    public static function insert(string $tableName, array $insertData): int
    {
        $columnsArr = array_keys($insertData);
        $columnsStr = implode(',', $columnsArr);
        $columnsStrParams = implode(',', array_map(fn($col) => ':' . $col, $columnsArr));

        $query = "INSERT INTO $tableName ($columnsStr) VALUES ($columnsStrParams)";
        $statement = self::getInstance()->prepare($query);
        $statement->execute($insertData);
        return (int)self::getInstance()->lastInsertId();
    }

    public static function insertAll(string $tableName, array $insertData): void
    {
        foreach ($insertData as $row) {
            self::insert($tableName, $row);
        }
    }

    public static function update(string $tableName, array $updateData, string $where)
    {
        $columnsArr = array_keys($updateData);
        $columnsStrParams = implode(',', array_map(fn($col) => $col . ' = :' . $col, $columnsArr));

        $query = "UPDATE $tableName SET $columnsStrParams WHERE $where";
        $statement = self::getInstance()->prepare($query);
        $statement->execute($updateData);
    }

    public static function fetchAll(string $query): array
    {
        $statement = self::getInstance()->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}
