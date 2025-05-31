<?php
namespace app\controllers;

use core\App;
use core\ParamUtils;
use core\SessionUtils;
use core\RoleUtils;
use core\Message;
use app\models\UserModel;

class LoginCtrl {

    
    public function action_login(): void {
        $smarty = App::getSmarty();

        // GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $smarty->display('auth/login.tpl');
            return;
        }

        // POST
        $username = ParamUtils::getFromPost('username');
        $password = ParamUtils::getFromPost('password');
        $msgs     = App::getMessages();

        if (empty($username) || empty($password)) {
            $msgs->addMessage(new Message('Login i hasło są wymagane.', Message::ERROR));
            $smarty->display('auth/login.tpl');
            return;
        }

        $user = UserModel::findByUsername($username);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $msgs->addMessage(new Message('Niepoprawny login lub hasło.', Message::ERROR));
            $smarty->display('auth/login.tpl');
            return;
        }

        SessionUtils::storeObject('user', $user);              
        RoleUtils::addRole($user['role_id']);

        $msgs->addMessage(new Message('Zalogowano pomyślnie.', Message::INFO));
        App::getRouter()->redirectTo('home');
    }

    public function action_logout(): void {
        SessionUtils::remove('user');
        SessionUtils::remove('_amelia_roles');
        App::getMessages()->addMessage(new Message('Wylogowano pomyślnie.', Message::INFO));
        App::getRouter()->redirectTo('login');
    }
}
