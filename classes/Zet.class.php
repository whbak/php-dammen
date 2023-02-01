<?php

namespace Dammen;

class Zet
{
    public $vanPositie;
    public $naarPositie;

    public function __construct($vanPositie, $naarPositie)
    {
        $this->vanPositie = $vanPositie;
        $this->naarPositie = $naarPositie;
    }
}
