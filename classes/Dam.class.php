<?php

namespace Dammen;

class Dam extends AbstractSteen
{
    public $zet;
    public $bord;
    public $spelerAanDeBeurt;

    public function __construct()
    {
    }
    public function check($zet, $bord, $spelerAanDeBeurt)
    {
        /* checklist check via check functie */
		$checklist = new RegelControleur();
		if ($checklist->checklist('dam') <> true) {
			return false;
		}
        if (isset($zet, $bord, $spelerAanDeBeurt)) {
            return $this->kanAchteruitSlaan($zet, $bord, $spelerAanDeBeurt);
        } else {
            false;
        }
    }

	public function kanAchteruitSlaan($beschikbareVakken, $bord, $speler)
    {
        $mogelijkeSlagen = [];
        if ($speler === 0) {
            $beweegRichting = 1;
        } else {
            $beweegRichting = -1;
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
        return $mogelijkeSlagen;
    }
}