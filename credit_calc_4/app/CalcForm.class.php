<?php
class CalcForm {
    public $credit;
    public $percent;
    public $years;

    public function __construct($credit = null, $percent = null, $years = null) {
        $this->credit = $credit;
        $this->percent = $percent;
        $this->years = $years;
    }
}
