<?php

namespace Dammen;

class RegelControleur
{
    public function isGeldigeZet($zet, $bord, $spelerAanDeBeurt)
    {
        if (!$this->isGeldigeSpeler($spelerAanDeBeurt)) {
            return false;
        }
        if (!$this->bevatSteen(new Positie($zet->vanPositie->x, $zet->vanPositie->y), $bord)) {
            return false;
        }
        if (!$this->zetIsBinnenBord($zet)) {
            return false;
        }
        $positiesBeschikbareStenen = $this->vakkenVanBeschikbareStenen($bord, $spelerAanDeBeurt);
        $mogelijkeSlagen = $this->mogelijkeSlagen($positiesBeschikbareStenen, $bord, $spelerAanDeBeurt);
        if (count($mogelijkeSlagen) > 0) {
            if (in_array($zet, $mogelijkeSlagen)) {
                return true;
            } else {
                return false;
            }
        } else {
            $mogelijkeZetten = $this->mogelijkeZetten($positiesBeschikbareStenen, $bord, $spelerAanDeBeurt);
            if (in_array($zet, $mogelijkeZetten)) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function zetIsBinnenBord($zet)
    {
        if (!$this->positieIsBinnenBord($zet->vanPositie) || !$this->positieIsBinnenBord($zet->naarPositie)) {
            return false;
        }
        return true;
    }

    private function positieIsBinnenBord($positie)
    {
        if ($positie->x > 9 || $positie->x < 0) {
            return false;
        }
        if ($positie->y > 9 || $positie->y < 0) {
            return false;
        }
        return true;
    }

    private function isGeldigeSpeler($speler)
    {
        if ($speler === 0 || $speler === 1) {
            return true;
        }
        return false;
    }

    private function bevatSteen($positie, $bord)
    {
        if (($bord->vakjes[$positie->y][$positie->x]->steen instanceof Steen)) {
            return true;
        }
        return false;
    }

    private function bevatSteenVanTegenstander($positie, $bord, $spelerAanDeBeurt)
    {
        if ($this->bevatSteen($positie, $bord)) {
            if ($bord->vakjes[$positie->y][$positie->x]->steen->kleur === $this->kleurVanSpeler(1 - $spelerAanDeBeurt)) {
                return true;
            }
        }
        return false;
    }

    private function kleurVanSpeler($speler)
    {
        if ($speler === 0) {
            return "wit";
        }
        return "zwart";
    }

    private function vakkenVanBeschikbareStenen($bord, $spelerAanDeBeurt)
    {
        $spelerKleur = $this->kleurVanSpeler($spelerAanDeBeurt);
        $beschikbareVakken = [];
        foreach ($bord->vakjes as $rijNummer => $rij) {
            foreach ($rij as $kolomNummer => $vak) {
                if (isset($vak->steen)) {
                    if ($vak->steen->kleur === $spelerKleur) {
                        $beschikbareVakken[] = new Positie($kolomNummer, $rijNummer);
                    }
                }
            }
        }
        return $beschikbareVakken;
    }

    
    private function mogelijkeZetten($beschikbareVakken, $bord, $speler)
    {
        $mogelijkeZetten = [];
        if ($speler === 0) {
            $beweegRichting = -1;
        } else {
            $beweegRichting = 1;
        }
        foreach ($beschikbareVakken as $steenPositie) {
            $naar = new Positie(($steenPositie->x + 1), ($steenPositie->y + $beweegRichting));
            if ($this->positieIsBinnenBord($naar) && !$this->bevatSteen($naar, $bord)) {
                $mogelijkeZetten[] = new Zet($steenPositie, $naar);
            }
            $naar = new Positie(($steenPositie->x - 1), ($steenPositie->y + $beweegRichting));
            if ($this->positieIsBinnenBord($naar) && !$this->bevatSteen($naar, $bord)) {
                $mogelijkeZetten[] = new Zet($steenPositie, $naar);
            }
        }
        return $mogelijkeZetten;
    }

    private function mogelijkeSlagen($beschikbareVakken, $bord, $speler)
    {
        $mogelijkeSlagen = [];
        if ($speler === 0) {
            $beweegRichting = -1;
        } else {
            $beweegRichting = 1;
        }
        foreach ($beschikbareVakken as $steenPositie) {
            $naar = new Positie(($steenPositie->x + 2), ($steenPositie->y + ($beweegRichting * 2)));  // + 2 or -2) werkt niet, copy regel * -2 errort
            $over = new Positie(($steenPositie->x + 1), ($steenPositie->y + $beweegRichting));
            if (
                $this->positieIsBinnenBord($naar) 
                && !$this->bevatSteen($naar, $bord)
                && $this->bevatSteenVanTegenstander($over, $bord, $speler)
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
            $naar = new Positie(($steenPositie->x - 2), ($steenPositie->y + ($beweegRichting * 2)));
            $over = new Positie(($steenPositie->x - 1), ($steenPositie->y + $beweegRichting));
            if (
                $this->positieIsBinnenBord($naar)
                && !$this->bevatSteen($naar, $bord)
                && $this->bevatSteenVanTegenstander($over, $bord, $speler)    
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
        }
        /* begin gehakt voor terugslag beweegrichting: achteruit */
        if ($speler === 0) {
            $beweegRichting = 1; // aangepast was -1
        } else {
            $beweegRichting = -1; // aangepast was 1
        }
        foreach ($beschikbareVakken as $steenPositie) {
            $naar = new Positie(($steenPositie->x + 2), ($steenPositie->y + ($beweegRichting * 2)));  // + 2 or -2) werkt niet, copy regel * -2 errort
            $over = new Positie(($steenPositie->x + 1), ($steenPositie->y + $beweegRichting));
            if (
                $this->positieIsBinnenBord($naar) 
                && !$this->bevatSteen($naar, $bord)
                && $this->bevatSteenVanTegenstander($over, $bord, $speler)
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
            $naar = new Positie(($steenPositie->x - 2), ($steenPositie->y + ($beweegRichting * 2)));
            $over = new Positie(($steenPositie->x - 1), ($steenPositie->y + $beweegRichting));
            if (
                $this->positieIsBinnenBord($naar)
                && !$this->bevatSteen($naar, $bord)
                && $this->bevatSteenVanTegenstander($over, $bord, $speler)    
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
        }
        /* einde gehakt voor terugslag beweegrichting: achteruit */
        return $mogelijkeSlagen;
    }
}