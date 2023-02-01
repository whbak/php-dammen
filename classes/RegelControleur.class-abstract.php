<?php

namespace Dammen;

require_once 'Regelinterface.class.php';
class RegelControleur
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
	/* checklist check via check functie */
	public function checklist($functie)
	{
		$checklist = [
            'isGeldigeZet',
			'isGeldigeSpeler',
            'bevatSteen',
            'zetIsBinnenBord',
            'positieIsBinnenBord',
            'vakkenVanBeschikbareStenen',
            'kleurVanSpeler',
            'MogelijkeSlagen',
            'bevatSteenVanTegenstander',
            'mogelijkeZetten',
            'dam'
		];
		if (in_array($functie, $checklist)) {
			return true;
		}
	}

    public function isGeldigeZet($zet, $bord, $spelerAanDeBeurt)
    {
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            $isGeldigeZet = new isGeldigeZet();
            if ($isGeldigeZet-> check($zet, $bord, $spelerAanDeBeurt) == true) {
                /* isGeldigeZet returnt true naar DamSpel */
                return true;
            } else {
                /* isGeldigeZet returnt false naar DamSpel */
                return false;
            }
        } else {
            return false;
        }
    }
}

class isGeldigeZet implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('isGeldigeZet') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if ($this->isGeldigeZet($zet, $bord, $spelerAanDeBeurt) <> true) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function isGeldigeZet($zet, $bord, $spelerAanDeBeurt)
    {
        $isGeldigeSpeler = new isGeldigeSpeler();
        if (!$isGeldigeSpeler->check($zet, $bord, $spelerAanDeBeurt)) {
            return false;
        }

        $bevatSteen = new bevatSteen();
        if (!$bevatSteen->check($zet->vanPositie, $bord, $spelerAanDeBeurt)) {
            return false;
        }

        $zetIsBinnenBord = new zetIsBinnenBord();
        if (!$zetIsBinnenBord->check($zet, $bord, $spelerAanDeBeurt)) {
            return false;
        }

        /* nieuwe aanroep wegens functie in class met aangepaste $'s */
        $positiesBeschikbareStenen = new vakkenVanBeschikbareStenen();
        $pBS = $positiesBeschikbareStenen->check($zet, $bord, $spelerAanDeBeurt);
        /* nieuwe aanroep wegens functie in class met aangepaste $'s */
        $MogelijkeSlagen = new MogelijkeSlagen();
        $MS = $MogelijkeSlagen->check($pBS, $bord, $spelerAanDeBeurt);
        /* nieuwe aanroep wegens functie in class met aangepaste $'s */
        $mogelijkeZetten = new mogelijkeZetten();
        $MZ = $mogelijkeZetten->check($pBS, $bord, $spelerAanDeBeurt);
        if (count($MS) > 0) {
            if (in_array($zet, $MS)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (in_array($zet, $MZ)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

class zetIsBinnenBord implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('zetIsBinnenBord') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if ($this->zetIsBinnenBord($zet, $bord, $spelerAanDeBeurt) == true) {
                return true;
            }
        } else {
            return false;
        }
    }

    private function zetIsBinnenBord($zet, $bord, $spelerAanDeBeurt)
    {
        $positieIsBinnenBord = new positieIsBinnenBord();
        if (
            (!$positieIsBinnenBord->check($zet->vanPositie, $bord, $spelerAanDeBeurt))
            || (!$positieIsBinnenBord->check($zet->naarPositie, $bord, $spelerAanDeBeurt))
        ) {
            return false;
        } else {
            return true;
        }
    }
}

class positieIsBinnenBord implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $positie;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('positieIsBinnenBord') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if (!$this->positieIsBinnenBord($zet)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
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
}

class isGeldigeSpeler implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $speler;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('isGeldigeSpeler') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if ($this->isGeldigeSpeler($spelerAanDeBeurt) == true) {
                return true;
            }
        } else {
            return false;
        }
    }

    private function isGeldigeSpeler($speler)
    {
        if ($speler === 0 || $speler === 1) {
            return true;
        }
        return false;
    }
}

class bevatSteen implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $positie;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('bevatSteen') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if ($this->bevatSteen(new Positie($zet->x, $zet->y), $bord) == true) {
                return true;
            }
        } else {
            return false;
        }
    }
    private function bevatSteen($positie, $bord)
    {
        if (($bord->vakjes[$positie->y][$positie->x]->steen instanceof Steen)) {
            return true;
        }
        return false;
    }
}

class bevatSteenVanTegenstander implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $positie;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('bevatSteenVanTegenstander') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            if ($this->bevatSteenVanTegenstander($zet, $bord, $spelerAanDeBeurt) == true) {
                return true;
            }
        } else {
            return false;
        }
    }

    private function bevatSteenVanTegenstander($positie, $bord, $spelerAanDeBeurt)
    {
        $bevatSteen = new bevatSteen();
        $BS = $bevatSteen->check($positie, $bord, $spelerAanDeBeurt);
        $kleurVanSpeler = new kleurVanSpeler();
        if ($BS) {
            if ($bord->vakjes[$positie->y][$positie->x]->steen->kleur === $kleurVanSpeler->check($positie, $bord, (1 - $spelerAanDeBeurt))) {
                return true;
            }
        }
        return false;
    }
}

class kleurVanSpeler implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $peler;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('kleurVanSpeler') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            return $this->kleurVanSpeler($spelerAanDeBeurt);
        } else {
            return false;
        }
    }

    private function kleurVanSpeler($speler)
    {
        if ($speler === 0) {
            return "wit";
        }
        return "zwart";
    }
}

class vakkenVanBeschikbareStenen implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $beschikbareVakken;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
        $checklist = new RegelControleur();
        if ($checklist->checklist('vakkenVanBeschikbareStenen') <> true) {
            return false;
        }
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            return $this->vakkenVanBeschikbareStenen($zet, $bord, $spelerAanDeBeurt);
        } else {
            return false;
        }
    }

    private function vakkenVanBeschikbareStenen($zet, $bord, $spelerAanDeBeurt)
    {
        $spelerKleur = new kleurVanSpeler();
        $SK = $spelerKleur->check($zet, $bord, $spelerAanDeBeurt);
        $beschikbareVakken = [];
        foreach ($bord->vakjes as $rijNummer => $rij) {
            foreach ($rij as $kolomNummer => $vak) {
                if (isset($vak->steen)) {
                    if ($vak->steen->kleur === $SK) {
                        $beschikbareVakken[] = new Positie($kolomNummer, $rijNummer);
                    }
                }
            }
        }
        return $beschikbareVakken;
    }
}

class mogelijkeZetten implements Regelinterface
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;
    public $mogelijkeZetten;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('mogelijkeZetten') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            return $this->mogelijkeZetten($zet, $bord, $spelerAanDeBeurt);
        } else {
            return false;
        }
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
            $positieIsBinnenBord = new positieIsBinnenBord();
            $bevatSteen = new bevatSteen();
            if ($positieIsBinnenBord->check($naar, $bord, $speler) && !$bevatSteen->check($naar, $bord, $speler)) {
                $mogelijkeZetten[] = new Zet($steenPositie, $naar);
            }
            $naar = new Positie(($steenPositie->x - 1), ($steenPositie->y + $beweegRichting));
            $positieIsBinnenBord = new positieIsBinnenBord();
            $bevatSteen = new bevatSteen();
            if ($positieIsBinnenBord->check($naar, $bord, $speler) && !$bevatSteen->check($naar, $bord, $speler)) {
                $mogelijkeZetten[] = new Zet($steenPositie, $naar);
            }
        }
         return $mogelijkeZetten;
    }
}

class MogelijkeSlagen implements Regelinterface
{
    public $zet = [];
    public $bord;
    public $spelerAanDeBeurt;
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('MogelijkeSlagen') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            return $this->mogelijkeSlagen($zet, $bord, $spelerAanDeBeurt);
        } else {
            return false;
        }
    }

    private function mogelijkeSlagen($beschikbareVakken, $bord, $speler)
    {
        $mogelijkeSlagen = [];
        if ($speler === 0) {
            $beweegRichting = -1;
        } else {
            $beweegRichting = 1;
        }
        $positieIsBinnenBord = new positieIsBinnenBord();
        $bevatSteen = new bevatSteen();
        $bevatSteenVanTegenstander = new bevatSteenVanTegenstander();
        foreach ($beschikbareVakken as $steenPositie) {
            $naar = new Positie(($steenPositie->x + 2), ($steenPositie->y + ($beweegRichting * 2)));
            $over = new Positie(($steenPositie->x + 1), ($steenPositie->y + $beweegRichting));
            if (
                /* nieuwe aanroep wegens functie in class met aangepaste $'s */
                $positieIsBinnenBord->check($naar, $bord, $speler)
                && !$bevatSteen->check($naar, $bord, $speler)
                && $bevatSteenVanTegenstander->check($over, $bord, $speler)
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
            $naar = new Positie(($steenPositie->x - 2), ($steenPositie->y + ($beweegRichting * 2)));
            $over = new Positie(($steenPositie->x - 1), ($steenPositie->y + $beweegRichting));
            if (
                /* nieuwe aanroep wegens functie in class met aangepaste $'s */
                $positieIsBinnenBord->check($naar, $bord, $speler)
                && !$bevatSteen->check($naar, $bord, $speler)
                && $bevatSteenVanTegenstander->check($over, $bord, $speler)
            ) {
                $mogelijkeSlagen[] = new Zet($steenPositie, $naar);
            }
        }

        /* aanroep nieuwe dam.class.php */
        $dam = new Dam();
        $slagachteruit = $dam->check($beschikbareVakken, $bord, $speler);
        if (count($slagachteruit) > 0) {
            $mogelijkeSlagen[] = $slagachteruit[0];
        }
        return $mogelijkeSlagen;
    }
}