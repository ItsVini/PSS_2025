<?php
require_once 'D:\tools\htdocs\credit_calc_4\smarty\libs\Smarty.class.php';
require_once 'CalcForm.class.php';
require_once 'CalcResult.class.php';
require_once 'Messages.class.php';

class CalcCtrl {
    private $form;
    private $result;
    private $messages;
    private $smarty;

    public function __construct() {
        $this->form = new CalcForm();
        $this->result = new CalcResult();
        $this->messages = new Messages();
        $this->smarty = new Smarty();
    }

    public function getParams() {
        $this->form->credit = isset($_REQUEST['credit']) ? $_REQUEST['credit'] : null;
        $this->form->percent = isset($_REQUEST['percent']) ? $_REQUEST['percent'] : null;
        $this->form->years = isset($_REQUEST['years']) ? $_REQUEST['years'] : null;
    }

    public function validate() {
        if ($this->form->credit === null || $this->form->credit === '') {
            $this->messages->addMessage('Nie podano kwoty kredytu');
        }
        if ($this->form->percent === null || $this->form->percent === '') {
            $this->messages->addMessage('Nie podano oprocentowania');
        }
        if ($this->form->years === null || $this->form->years === '') {
            $this->messages->addMessage('Nie podano liczby lat');
        }
    }

    public function process() {
        if (empty($this->messages->messages)) {
            $credit = (float) $this->form->credit;
            $percent = (float) $this->form->percent;
            $years = (int) $this->form->years;

            $monthly = ($credit + ($percent * $credit / 100)) / ($years * 12);
            $this->result->monthlyPayment = number_format($monthly, 2, '.', '');
        }
    }

    public function generateView() {
        $this->smarty->assign('form', $this->form);
        $this->smarty->assign('messages', $this->messages);
        $this->smarty->assign('result', $this->result);
        $this->smarty->display('D:\tools\htdocs\credit_calc_4\templates\CalcView.tpl');
    }
}
