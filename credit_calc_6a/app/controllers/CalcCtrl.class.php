<?php
namespace app\controllers;


use app\forms\CalcForm;
use app\transfer\CalcResult;
use core\Messages;

class CalcCtrl {

	private $msgs;  
	private $form;  
	private $result; 


	public function __construct(){
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
	}
	

	public function getParams(){
		$this->form->kwota = isset($_REQUEST ['kwota']) ? $_REQUEST ['kwota'] : null;
		$this->form->lata = isset($_REQUEST ['lata']) ? $_REQUEST ['lata'] : null;
		$this->form->oprocentowanie = isset($_REQUEST ['oprocentowanie']) ? $_REQUEST ['oprocentowanie'] : null;
	}
	
	public function validate() {
		if (! (isset ( $this->form->kwota ) && isset ( $this->form->lata ) && isset ( $this->form->oprocentowanie ))) {
			return false; 
		}
		
		if ($this->form->kwota == "") {
			$this->msgs->addError('Nie podano kwoty');
		}
		if ($this->form->lata == "") {
			$this->msgs->addError('Nie podano lat');
		}
		if ($this->form->oprocentowanie == "") {
			$this->msgs->addError('Nie podano oprocentowania');
		}
		if (! $this->msgs->isError()) {
			
			if (! is_numeric ( $this -> form -> kwota )) {
				$this->msgs->addError('Kwota musi być liczbą całkowitą');
			}
			
			if (! is_numeric ( $this -> form -> lata )) {
				$this->msgs->addError('Lata musi być liczbą całkowitą');
			}
                        if (! is_numeric ( $this -> form -> oprocentowanie )) {
				$this->msgs->addError('Oprocentowanie musi być liczbą całkowitą');
			}
		}
		
		return ! $this->msgs->isError();
	}
	
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			$this-> form -> kwota = intval($this -> form -> kwota);
			$this-> form -> lata = intval($this -> form -> lata);
            $this-> form -> oprocentowanie = intval($this -> form -> oprocentowanie);
			$this-> msgs -> addInfo('Parametry prawidłowe.');
			$this-> result -> result  = ($this -> form -> kwota + ($this -> form -> lata * $this -> form -> oprocentowanie) / 100) / ($this -> form -> lata * 12);	
			$this-> msgs -> addInfo('Wykonano obliczenia.');
		}
		
		$this->generateView();
	}
	
	
	public function generateView(){
		global $user;
		getSmarty()->assign('user',$user);
				
		getSmarty()->assign('form',$this->form);
		getSmarty()->assign('res',$this->result);
		
		getSmarty()->display('CalcView.tpl'); 
	}
}
