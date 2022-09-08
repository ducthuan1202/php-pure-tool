<?php


namespace Src;


class App
{
    public function __construct()
    {
        $users = UserService::getUsers();
        dump($users);

        $admin = UserService::getUserById(1);
        dump($admin);

        $result = UserService::updateWithTransaction();
        dump($result);
    }
}