<?php

namespace Dammen;

interface Regelinterface
{
	public function check($zet, $bord, $spelerAanDeBeurt);
}