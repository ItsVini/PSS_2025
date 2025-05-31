<?php



require_once __DIR__ . '/core/Config.class.php';
require_once __DIR__ . '/core/App.class.php';
require_once __DIR__ . '/core/SessionUtils.class.php';

use core\Config;
use core\App;
use core\SessionUtils;

/** @var Config $conf */
$conf = new Config();

$conf->action_param  = 'action';       
$conf->action_script = '/ctrl.php';      

require_once __DIR__ . '/config.php';

$conf->root_path = __DIR__;
$conf->server_url = $conf->protocol . '://' . $conf->server_name;
$conf->app_url    = $conf->server_url . $conf->app_root;
if ($conf->clean_urls) {
    $conf->action_root = $conf->app_root . '/';
} else {
    $conf->action_root = $conf->app_root . '/index.php?' . $conf->action_param . '=';
}
$conf->action_url = $conf->server_url . $conf->action_root;

App::createAndInitialize($conf);

/** @var \Smarty $smarty */
$smarty = App::getSmarty();
$smarty->assign('conf', App::getConf());
$smarty->assign('msgs', App::getMessages());
use core\Message;

$allMsgs = App::getMessages()->getMessages();
$errors = [];
$infos  = [];
foreach ($allMsgs as $m) {
    if ($m->type === Message::ERROR) {
        $errors[] = $m->text;
    }
    if ($m->type === Message::INFO) {
        $infos[] = $m->text;
    }
}
$smarty->assign('errors', $errors);
$smarty->assign('infos',  $infos);

$smarty->assign('user', SessionUtils::loadObject('user', true));
$smarty->assign('form', array_merge($_GET, $_POST));

require_once __DIR__ . '/routing.php';
App::getRouter()->go();
