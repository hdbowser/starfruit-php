<?php

namespace App\Core;
use Medoo\Medoo;
class Data
{
    public static function getDBContext()
    {
        return new Medoo([
            'database_type' => 'sqlite',
            'database_file' => 'data.sqlite',
            'loggin' => true
        ]);
    }
}
