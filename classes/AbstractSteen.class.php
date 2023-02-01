<?php

namespace Dammen;

abstract class AbstractSteen
{
    public function __construct()
    {
    }
    abstract public function kanAchteruitSlaan($zet, $bord, $spelerAanDeBeurt);
}