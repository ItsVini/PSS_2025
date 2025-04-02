<?php
class CalcResult {
    public $monthlyPayment;

    public function __construct($monthlyPayment = null) {
        $this->monthlyPayment = $monthlyPayment;
    }
}
