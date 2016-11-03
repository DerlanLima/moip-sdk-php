<?php

namespace Softpampa\Moip\Helpers;

use stdClass;

class Boleto extends SharedObjects {

    protected $expirationDate;

    protected $instructions;

    protected $logo;

    public function __construct($expirationDate, $instructions = [], $logo)
    {
        $this->expirationDate = $expirationDate;
        $this->instructions = $instructions;
        $this->logo = $logo;
    }

    protected function prepareDataForSubscriptions()
    {
        // Not applied
    }

    protected function prepareDataForPayments()
    {
        $boleto = new stdClass;
        $boleto->expirationDate = $this->expirationDate;
        $boleto->logoUri = $this->logo;
        $boleto->instructionLines = new stdClass;
        $boleto->instructionLines->first = isset($this->instructions[0]) ? $this->instructions[0] : null;
        $boleto->instructionLines->second = isset($this->instructions[1]) ? $this->instructions[1] : null;
        $boleto->instructionLines->third = isset($this->instructions[2]) ? $this->instructions[2] : null;

        $this->data = $boleto;
    }

}
