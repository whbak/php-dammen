<?php

namespace Dammen;

class Bord
{
    public $vakjes;
    public $kleuren;

    public function __construct()
    {
        $this->kleuren = new Colors();
        $this->vakjes = [];
        for ($rij = 0; $rij < 10; $rij++) {
            $rijVakjes = [];
            for ($kolom = 0; $kolom < 10; $kolom++) {
                if ((($rij % 2) + ($kolom % 2)) % 2 == 0) {
                    if ($rij < 4) {
                        $rijVakjes[] = new Vak("wit", new Steen("zwart"));
                    } elseif ($rij > 5) {
                        $rijVakjes[] = new Vak("wit", new Steen("wit"));
                    } else {
                        $rijVakjes[] = new Vak("wit");
                    }
                } else {
                    $rijVakjes[] = new Vak("zwart");
                }
            }
            $this->vakjes[] = $rijVakjes;
        }
    }

    public function voerZetUit($zet)
    {
        $zetSteenRij = $zet->vanPositie->y;
        $zetSteenKolom = $zet->vanPositie->x;
        $naarRij = $zet->naarPositie->y;
        $naarKolom = $zet->naarPositie->x;
        $zetSteen = $this->vakjes[$zetSteenRij][$zetSteenKolom]->steen;
        $this->vakjes[$naarRij][$naarKolom]->steen = $zetSteen;
        $this->vakjes[$zetSteenRij][$zetSteenKolom]->steen = null;
    }

    public function printStatus()
    {
        $rijNummer = 0;
        foreach ($this->vakjes as $rijVakjes) {
            print_r($this->kleuren->getColoredString($rijNummer . " ", "white", "magenta"));
            $rijNummer += 1;
            foreach ($rijVakjes as $vak) {
                if ($vak->kleur === "wit") {
                    $achtergrondKleur = "light_gray";
                } else {
                    $achtergrondKleur = "black";
                }
                if (isset($vak->steen)) {
                    $steen = "()";
                    if ($vak->steen->kleur === "wit") {
                        $voorgrondKleur = "light_blue";
                    } else {
                        $voorgrondKleur = "black";
                    }
                } else {
                    $steen = "  ";
                    $voorgrondKleur = null;
                }
                print_r($this->kleuren->getColoredString($steen, $voorgrondKleur, $achtergrondKleur));
            }
            print_r(PHP_EOL);
        }
        print_r($this->kleuren->getColoredString("  ", "white", "magenta"));
        for ($kolomNummer = 0; $kolomNummer < 10; $kolomNummer++) { 
            print_r($this->kleuren->getColoredString($kolomNummer . " ", "white", "magenta"));
        }
        print_r(PHP_EOL);
    }
}
