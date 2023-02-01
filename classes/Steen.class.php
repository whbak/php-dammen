<?php

namespace Dammen;

class Steen extends AbstractSteen
{
    public $kleur;

    public function __construct($kleur)
    {
        $this->kleur = $kleur;
    }

    public function kanAchteruitSlaan($zet, $bord, $spelerAanDeBeurt)
    {
    }
}