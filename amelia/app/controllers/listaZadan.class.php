<?php
namespace app\controllers;

use core\App;
use core\SessionUtils;


class listaZadan {
    public function action_lista(): void {
        $user = SessionUtils::loadObject('user', true);
                $smarty  = App::getSmarty();
        App::getSmarty()->assign('user', $user);
        $smarty->display('task/ilosc.tpl');
    }
}
