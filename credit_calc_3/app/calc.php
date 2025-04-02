<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../smarty_config.php';

$credit = $_POST['credit'] ?? '';
$percent = $_POST['percent'] ?? '';
$years = $_POST['years'] ?? '';

$messages = [];

if (!empty($_POST)) {
    if ($credit === '') {
        $messages[] = 'Nie podano kwoty kredytu';
    }
    if ($percent === '') {
        $messages[] = 'Nie podano oprocentowania';
    }
    if ($years === '') {
        $messages[] = 'Nie podano liczby lat';
    }
}

if (empty($messages) && $credit !== '' && $percent !== '' && $years !== '') {
    $credit = (float)$credit;
    $percent = (float)$percent;
    $years = (int)$years;

    $monthly = ($credit + ($percent * $credit / 100)) / ($years * 12);
    $result = number_format($monthly, 2, '.', '');
}

$smarty->assign('credit', $credit);
$smarty->assign('percent', $percent);
$smarty->assign('years', $years);
$smarty->assign('messages', $messages ?? []);
$smarty->assign('result', $result ?? '');

$smarty->display('calc_view.tpl');
