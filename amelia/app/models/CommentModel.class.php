<?php
namespace app\models;
use core\App;

class CommentModel {
    protected static $table = 'comments';

    public static function getForTask(int $taskId): array {
        return App::getDB()->select(
            self::$table,
            ['id','author_id','content','created_at','updated_at'],
            ['task_id' => $taskId, 'ORDER' => ['created_at' => 'ASC']]
        );
    }

    public static function create(array $data): int {
        App::getDB()->insert(self::$table, $data);
        return (int) App::getDB()->id();
    }
}
