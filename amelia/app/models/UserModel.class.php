<?php
namespace app\models;

use core\App;
use Medoo\Medoo;

class UserModel {
    protected static $table = 'users';

    public static function create(array $data): int {
        $db = App::getDB();
        $db->insert(self::$table, $data);
        return (int) $db->id();
    }

    public static function getAll(): array {
        $db = App::getDB();
        return $db->select(
            self::$table,
            ['id', 'username', 'first_name', 'last_name', 'email', 'role_id', 'created_at']
        );
    }

    public static function findById(int $id): ?array {
        $user = App::getDB()->get(
            self::$table,
            ['id', 'username', 'first_name', 'last_name', 'email', 'role_id', 'created_at'],
            ['id' => $id]
        );
        return $user ?: null;
    }

    public static function findByUsername(string $username): ?array {
        $user = App::getDB()->get(
            self::$table,
            ['id', 'username', 'first_name', 'last_name', 'password_hash', 'email', 'role_id', 'created_at'],
            ['username' => $username]
        );
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array {
        $user = App::getDB()->get(
            self::$table,
            ['id', 'username', 'first_name', 'last_name', 'password_hash', 'email', 'role_id', 'created_at'],
            ['email' => $email]
        );
        return $user ?: null;
    }

    public static function update(int $id, array $data): int {
        $stmt = App::getDB()->update(self::$table, $data, ['id' => $id]);
        return $stmt->rowCount();
    }
}
