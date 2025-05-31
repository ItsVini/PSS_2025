<?php
namespace app\models;

use core\App;

class StatusModel {
    protected static $table = 'statuses';

    public static function getAll(): array {
    return App::getDB()->select(self::$table,
        ['id','name'],
        [
            'ORDER' => ['id' => 'ASC']
        ]
    );
}


    public static function findById(int $id): ?array {
        $row = App::getDB()->get(self::$table, ['id','name'], ['id'=>$id]);
        return $row ?: null;
    }
}
