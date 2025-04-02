<?php
require_once 'D:\tools\htdocs\credit_calc_4\app\CalcCtrl.class.php';

$ctrl = new CalcCtrl();
$ctrl->getParams();
$ctrl->validate();
$ctrl->process();
$ctrl->generateView();
