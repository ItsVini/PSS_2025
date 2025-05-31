<?php

use core\App;
use core\Utils;

// Strona startowa
App::getRouter()->setDefaultRoute('home');
Utils::addRoute('home',     'HomeCtrl');

Utils::addRoute('lista',   'listaZadan');

// Rejestracja (RegisterCtrl)
Utils::addRoute('register', 'RegisterCtrl');

// Logowanie + wylogowanie (LoginCtrl)
Utils::addRoute('login',    'LoginCtrl');
Utils::addRoute('logout',   'LoginCtrl');

// plik routing.php, w sekcji tras
Utils::addRoute('list',   'TaskCtrl');
Utils::addRoute('create', 'TaskCtrl');
Utils::addRoute('edit',   'TaskCtrl');
Utils::addRoute('taskComment',   'TaskCtrl');

Utils::addRoute('users',       'UserCtrl');
Utils::addRoute('userEdit',   'UserCtrl');

