<?php

namespace Src;

class UserService
{
    public static function getUsers()
    {
        $sql = 'SELECT * FROM `users` WHERE 1 LIMIT 5 OFFSET 0';
        return Database::findAll($sql);
    }

    public static function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        return Database::findOne($sql, [':id' => $id]);
    }

    public static function updateWithTransaction()
    {
        $name = 'thuanvmo';
        return Database::transaction(function () use ($name) {

            $now = date('Y-m-d H:i:s');

            // 1. update name for user 1
            $sqlUpdate = 'UPDATE `users` SET `name` = :name, `updated_at` = :updated_at WHERE `id` = :id';
            $result1 = Database::update($sqlUpdate, [
                ':name' => $name,
                ':id' => 1,
                ':updated_at' => $now,
            ]);

            // 2. set remember_token is null for user 2
            $sqlUpdate = 'UPDATE `users` SET `remember_token` = NULL, `updated_at` = :updated_at WHERE `id` = :id';
            $result2 = Database::update($sqlUpdate, [
                ':id' => 2,
                ':updated_at' => $now,
            ]);

            return [$result1, $result2];
        });
    }
}
