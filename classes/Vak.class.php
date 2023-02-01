<?php

namespace Dammen;

class Vak
{
    public $kleur;
    public $steen;

    public function __construct($kleur, $steen = null)
    {
        $this->kleur = $kleur;
        $this->steen = $steen;
    }
}
