<?php

namespace Src;

class Sql
{
    public static function selectUsers(){
        return 'SELECT * FROM `users` WHERE 1 LIMIT 5 OFFSET 0';
    }
}
