<?php

namespace Dammen;

class Positie
{
    public $x;
    public $y;

    public function __construct($x, $y)
    {
        $this->x = intval($x);
        $this->y = intval($y);
    }
}