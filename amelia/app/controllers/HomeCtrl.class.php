<?php
namespace app\controllers;

use core\App;
use core\SessionUtils;

class HomeCtrl {
    public function action_home(): void {
        $user = SessionUtils::loadObject('user', true);
        App::getSmarty()->assign('user', $user);
        $news = [
  ['id'=>1,'title'=>'Nowa funkcja raportów','date'=>'2025-05-01','summary'=>'Dodaliśmy możliwość generowania raportów PDF...'],
  // ...
];
App::getSmarty()->assign('news', $news);

        App::getSmarty()->display('home.tpl');
    }
}
