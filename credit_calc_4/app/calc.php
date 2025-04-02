<?php
require_once 'CalcCtrl.class.php';

$ctrl = new CalcCtrl();
$ctrl->getParams();
$ctrl->validate();
$ctrl->process();
$ctrl->generateView();
