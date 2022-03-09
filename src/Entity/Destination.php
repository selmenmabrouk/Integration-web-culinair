<?php

namespace App\Entity;

class Destination
{
    private Pays $unPays;
    private Ville $uneVille;
    private Continent $unContinent;

    public function getContinent(): Continent
    {
        return $this->unContinent;
    }

    public function setContinent($continent)
    {
        $this->unContinent = $continent;
    }

    public function getPays(): ?Pays
    {
        if (isset($this->unPays)) {
            return $this->unPays;
        } else {
            return null;
        }
    }

    public function setPays(Pays $pays)
    {
        $this->unPays = $pays;
    }

    public function getVille(): Ville
    {
        return $this->uneVille;
    }

    public function setVille(Ville $ville)
    {
        $this->uneVille = $ville;
    }
}
