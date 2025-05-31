<?php
namespace app\controllers;

use core\App;
use core\SessionUtils;
use core\Message;
use core\ParamUtils;
use app\models\UserModel;
use app\models\RoleModel;
use app\models\TaskModel;

class UserCtrl {
    public function action_users(): void {
        $smarty  = App::getSmarty();
        $current = SessionUtils::loadObject('user', true);

        if (!$current || $current['role_id'] != 4) {
            App::getMessages()->addMessage(new Message('Brak dostępu.', Message::ERROR));
            App::getRouter()->redirectTo('home');
            return;
        }

        $findAll = TaskModel::findAll($current['id']);

        $users = UserModel::getAll();
        $roles = RoleModel::getAll();
        $roleMap = [];
foreach ($roles as $r) {
    $roleMap[$r['id']] = $r['name'];
}

$smarty->assign('roles',   $roles);
$smarty->assign('roleMap', $roleMap);
$smarty->assign('users',   $users);
        $smarty->assign('users', $users);
        $smarty->assign('roles', $roles);
        $smarty->display('user/list.tpl');
    }

    public function action_userEdit(): void {
        $smarty  = App::getSmarty();
        $current = SessionUtils::loadObject('user', true);

        if (!$current || $current['role_id'] != 4) {
            App::getMessages()->addMessage(new Message('Brak dostępu.', Message::ERROR));
            App::getRouter()->redirectTo('home');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $id   = ParamUtils::getFromGet('id');
            $user = UserModel::findById($id);
            if (!$user) {
                App::getMessages()->addMessage(new Message('Użytkownik nie istnieje.', Message::ERROR));
                App::getRouter()->redirectTo('users');
                return;
            }
            $roles = RoleModel::getAll();
            $smarty->assign('userToEdit', $user);
            $smarty->assign('roles',      $roles);
            $smarty->display('user/edit.tpl');
            return;
        }

        $id      = ParamUtils::getFromPost('id');
        $newRole = ParamUtils::getFromPost('role_id');
        $user    = UserModel::findById($id);

        if (!$user) {
            App::getMessages()->addMessage(new Message('Użytkownik nie istnieje.', Message::ERROR));
            App::getRouter()->redirectTo('users');
            return;
        }

        UserModel::update($id, ['role_id' => (int)$newRole]);
        App::getMessages()->addMessage(new Message('Rola użytkownika została zmieniona.', Message::INFO));
        App::getRouter()->redirectTo('users');
    }
}
