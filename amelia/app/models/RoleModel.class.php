<?php
namespace app\models;

use core\App;

class RoleModel {
    protected static $table = 'roles';

    public static function getAll(): array {
        return App::getDB()->select(self::$table, ['id', 'name']);
    }

    public static function getPairs(): array {
        $rows = self::getAll();
        $pairs = [];
        foreach ($rows as $r) {
            $pairs[$r['id']] = $r['name'];
        }
        return $pairs;
    }

    /**
     * @return array{id:int,name:string}|null
     */
    public static function findById(int $id): ?array {
        $role = App::getDB()->get(self::$table, ['id', 'name'], ['id' => $id]);
        return $role ?: null;
    }

    /**
     * @return array{id:int,name:string}|null
     */
    public static function findByName(string $name): ?array {
        $role = App::getDB()->get(self::$table, ['id', 'name'], ['name' => $name]);
        return $role ?: null;
    }

    /**
     * @param int 
     * @return int[] 
     */
    public static function allowedForEditor(int $editorRoleId): array {
        $allPairs = self::getPairs();

        if ($editorRoleId >= 4) {
            return array_keys($allPairs);
        }

        if ($editorRoleId === 3) {
            $allowed = [];
            foreach (['guest', 'employee'] as $name) {
                $role = self::findByName($name);
                if ($role) {
                    $allowed[] = $role['id'];
                }
            }
            return $allowed;
        }

        return [];
    }

    public static function canEditRole(int $editorRoleId, int $targetRoleId): bool {
        return in_array($targetRoleId, self::allowedForEditor($editorRoleId), true);
    }
}
