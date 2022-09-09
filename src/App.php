<?php

namespace Src;

class App
{
    public function __construct()
    {
        $this->ahref();
    }

    function ahref()
    {
        $ahref = new AhrefService();
        $ahref->login();
    }

    function db()
    {
        $users = UserService::getUsers();
        dump($users);

        $admin = UserService::getUserById(1);
        dump($admin);

        $result = UserService::updateWithTransaction();
        dump($result);
    }
}
