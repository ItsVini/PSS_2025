<?php
require_once 'init.php';

switch ($action) {
	default : // 'calcView'
		include_once 'app/controllers/CalcCtrl.class.php';
		$ctrl = new CalcCtrl ();
		$ctrl->generateView ();
	break;
	case 'calcCompute' :
		include_once 'app/controllers/CalcCtrl.class.php';
		$ctrl = new CalcCtrl ();
		$ctrl->process ();
	break;
	case 'action1' :
		print('hello');
	break;
	case 'action2' :
		print('goodbye');
	break;
}
