<?php
namespace app\controllers;

use core\App;
use core\ParamUtils;
use core\Message;
use app\models\UserModel;

class RegisterCtrl {
    public function action_register(): void {
        $smarty = App::getSmarty();
        $msgs   = App::getMessages();

        // GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $smarty->assign('form', []);
            $smarty->display('auth/register.tpl');
            return;
        }

        // POST
        $username        = ParamUtils::getFromPost('username');
        $firstName       = ParamUtils::getFromPost('first_name');
        $lastName        = ParamUtils::getFromPost('last_name');
        $email           = ParamUtils::getFromPost('email');
        $password        = ParamUtils::getFromPost('password');
        $passwordConfirm = ParamUtils::getFromPost('password_confirm');

        if (!$username || !$firstName || !$lastName || !$email || !$password || !$passwordConfirm) {
            $msgs->addMessage(new Message('Wszystkie pola są wymagane.', Message::ERROR));
        }
        elseif ($password !== $passwordConfirm) {
            $msgs->addMessage(new Message('Hasła nie są identyczne.', Message::ERROR));
        }
        elseif (UserModel::findByUsername($username)) {
            $msgs->addMessage(new Message('Ten login jest już zajęty.', Message::ERROR));
        }
        elseif (UserModel::findByEmail($email)) {
            $msgs->addMessage(new Message('Ten e-mail jest już zarejestrowany.', Message::ERROR));
        }

        if ($msgs->isError()) {
            $smarty->assign('form', array_merge($_GET, $_POST));
            $smarty->display('auth/register.tpl');
            return;
        }

        $data = [
          'username'      => $username,
          'first_name'    => $firstName,
          'last_name'     => $lastName,
          'email'         => $email,
          'password_hash' => password_hash($password, PASSWORD_DEFAULT),
          'role_id'       => 1,
          'created_at'    => date('Y-m-d H:i:s'),
        ];
        UserModel::create($data);

        $msgs->addMessage(new Message('Konto zostało utworzone.', Message::INFO));
        App::getRouter()->redirectTo('login');
    }
}
