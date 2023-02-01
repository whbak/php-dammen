<?php

namespace Dammen;

class DamSpel
{
    public $bord;
    public $spelerAanDeBeurt;
    public $regelControleur;
    public $userInterface;

    public function __construct()
    {
        $this->bord = new Bord();
        $this->spelerAanDeBeurt = 0;
        $this->regelControleur = new RegelControleur();
        $this->userInterface = new UserInterface();
    }

    public function start()
    {
        $winnaar = false;
        do {
            $this->bord->printStatus();
            do {
                $zet = $this->userInterface->vraagSpelerOmZet($this->spelerAanDeBeurt);
                $isGeldig = $this->regelControleur->isGeldigeZet($zet, $this->bord, $this->spelerAanDeBeurt);

                if (!$isGeldig) {
                    echo "Dit is geen geldige zet! Probeer het opnieuw.." . PHP_EOL . PHP_EOL;
                }
            } while (!$isGeldig);
            $this->bord->voerZetUit($zet);
            $this->spelerAanDeBeurt = 1 - $this->spelerAanDeBeurt;
        } while (!$winnaar);
    }
}
