<?php
namespace app\models;

use core\App;
use Medoo\Medoo;

class TaskModel {
    protected static $table = 'tasks';

    public static function create(array $data): int {
        $db = App::getDB();
        $db->insert(self::$table, $data);
        return (int) $db->id();
    }

    public static function findById(int $id): ?array {
        $row = App::getDB()->get(self::$table, '*', ['id' => $id]);
        return $row ?: null;
    }


        public static function findAll(int $UserId): int {
        $row = App::getDB()->count(self::$table,null, null, ['assigned_to' => $UserId]);
        return $row ?: null;
    }

    public static function update(int $id, array $data): int {
        $stmt = App::getDB()->update(self::$table, $data, ['id' => $id]);
        return $stmt->rowCount();
    }

    public static function getByStatus(int $statusId): array {
        return App::getDB()->select(self::$table, '*', [
            'status_id' => $statusId,
            'ORDER'     => ['start_date' => 'ASC'],
        ]) ?: [];
    }

    public static function getByStatusForUser(int $statusId, int $userId): array {
        return App::getDB()->select(self::$table, '*', [
            'status_id'   => $statusId,
            'assigned_to' => $userId,
            'ORDER'       => ['start_date' => 'ASC'],
        ]) ?: [];
    }

    public static function getFilteredForUser(int $userId, ?string $search = null, ?int $statusId = null): array {
        $where = [
            'tasks.assigned_to' => $userId,
            'ORDER'             => ['tasks.start_date' => 'ASC'],
        ];
        if ($statusId) {
            $where['tasks.status_id'] = $statusId;
        }
        if ($search) {
            $where['tasks.title[~]'] = $search;
        }

        return App::getDB()->select(
            self::$table,
            [
                "[>]statuses" => ['status_id'   => 'id'],
                "[>]users"    => ['assigned_to' => 'id'],
            ],
            [
                'tasks.id',
                'tasks.title',
                'tasks.start_date',
                'tasks.end_date',
                'tasks.status_id',
                'tasks.assigned_to',
                'statuses.name(status_name)',
                'users.first_name(u_first)',
                'users.last_name(u_last)',
            ],
            $where
        ) ?: [];
    }

    public static function getFilteredAll(?string $search = null, ?int $statusId = null, ?int $assignedTo = null): array {
        $where = [
            'ORDER' => ['tasks.start_date' => 'ASC'],
        ];
        if ($statusId) {
            $where['tasks.status_id'] = $statusId;
        }
        if ($search) {
            $where['tasks.title[~]'] = $search;
        }
        if ($assignedTo) {
            $where['tasks.assigned_to'] = $assignedTo;
        }

        return App::getDB()->select(
            self::$table,
            [
                "[>]statuses" => ['status_id'   => 'id'],
                "[>]users"    => ['assigned_to' => 'id'],
            ],
            [
                'tasks.id',
                'tasks.title',
                'tasks.start_date',
                'tasks.end_date',
                'tasks.status_id',
                'tasks.assigned_to',
                'statuses.name(status_name)',
                'users.first_name(u_first)',
                'users.last_name(u_last)',
            ],
            $where
        ) ?: [];
    }
}
