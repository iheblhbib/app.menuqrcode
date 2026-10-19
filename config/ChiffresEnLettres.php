<?php
class ChiffreEnLettrephp
{
//NE GERE PAS TOUT (les pluriels...)
    #Variables
    public $leChiffreSaisi;
    public $enLettrephp = '';
    public $chiffrephp = array(1 => "un", 2 => "deux", 3 => "trois", 4 => "quatre", 5 => "cinq", 6 => "six", 7 => "sept", 8 => "huit", 9 => "neuf", 10 => "dix", 11 => "onze", 12 => "douze", 13 => "treize", 14 => "quatorze", 15 => "quinze", 16 => "seize", 17 => "dix-sept", 18 => "dix-huit", 19 => "dix-neuf", 20 => "vingt", 30 => "trente", 40 => "quarante", 50 => "cinquante", 60 => "soixante", 70 => "soixante-dix", 80 => "quatre-vingt", 90 => "quatre-vingt-dix");

#Fonction de conversion appelee dans la feuille principale
    public function Conversionphp($sasie)
    {
        $this->enLettrephp = '';
        $sasie = trim($sasie);

#suppression des espaces qui pourraient exister dans la saisie
        $nombre = '';
        $laSsasie = explode(' ', $sasie);
        foreach ($laSsasie as $partie) {
            $nombre .= $partie;
        }

#suppression des zéros qui precederaient la saisie
        $nb = strlen($nombre);
        for ($i = 0; $i <= $nb;) {
            if (substr($nombre, $i, 1) == 0) {
                $nombre = substr($nombre, $i + 1);
                $nb = $nb - 1;
            } elseif (substr($nombre, $i, 1) != 0) {
                $nombre = substr($nombre, $i);
                break;
            }
        }
#echo $nombre;
        #$this->Supzéro($nombre);
        #le nombre de caract que comporte le nombre saisi de sa forme sans espace et sans 0 au debut
        $nb = strlen($nombre);
#echo $nb.'<br/ >';
        #$this->leChiffreSaisi=$nombre;
        #conversion du chiffre saisi en lettre selon les cas
        switch ($nb) {
            case 0:
                $this->enLettrephp = 'zéro';
            case 1:
                if ($nombre == 0) {
                    $this->enLettrephp = 'zéro';
                    break;
                } elseif ($nombre != 0) {
                    $this->Unitephp($nombre);
                    break;
                }

            case 2:
                $unite = substr($nombre, 1);
                $dizaine = substr($nombre, 0, 1);
                $this->Dizainephp(0, $nombre, $unite, $dizaine);
                break;

            case 3:
                $unite = substr($nombre, 2);
                $dizaine = substr($nombre, 1, 1);
                $centaine = substr($nombre, 0, 1);
                $this->Centainephp(0, $nombre, $unite, $dizaine, $centaine);
                break;

#cas des milles
            case ($nb > 3 and $nb <= 6):
                $unite = substr($nombre, $nb - 1);
                $dizaine = substr($nombre, ($nb - 2), 1);
                $centaine = substr($nombre, ($nb - 3), 1);
                $mille = substr($nombre, 0, ($nb - 3));
                $this->Millephp($nombre, $unite, $dizaine, $centaine, $mille);
                break;

#cas des millions
            case ($nb > 6 and $nb <= 9):
                $unite = substr($nombre, $nb - 1);
                $dizaine = substr($nombre, ($nb - 2), 1);
                $centaine = substr($nombre, ($nb - 3), 1);
                $mille = substr($nombre, -6);
                $million = substr($nombre, 0, $nb - 6);
                $this->Millionphp($nombre, $unite, $dizaine, $centaine, $mille, $million);
                break;

#cas des milliards
                /*case ($nb>9 and $nb<=12):
        $unite=substr($nombre,$nb-1);
        $dizaine=substr($nombre,($nb-2),1);
        $centaine=substr($nombre,($nb-3),1);
        $mille=substr($nombre,-6);
        $million=substr($nombre,-9);
        $milliard=substr($nombre,0,$nb-9);
        Milliard($nombre,$unite,$dizaine,$centaine,$mille,$million,$milliard);
        break;*/

        }
        if (!empty($this->enLettrephp)) {
            return $this->enLettrephp;
        }

    }

#Gestion des miiliards
    /*
    function Milliard($nombre,$unite,$dizaine,$centaine,$mille,$million,$milliard)
    {

    }
     */

#Gestion des millions

    public function Millionphp($nombre, $unite, $dizaine, $centaine, $mille, $million)
    {
#si les mille comportent un seul chiffre
        #$cent represente les 3 premiers chiffres du chiffre ex: 321 dans 12502321
        #$mille represente les 3 chiffres qui suivent les cents ex: 502 dans 12502321
        #reste represente les 6 premiers chiffres du chiffre ex: 502321 dans 12502321

        $cent = substr($nombre, -3);
        $reste = substr($nombre, -6);

        if (strlen($million) == 1) {
            $mille = substr($nombre, 1, 3);
            $this->enLettrephp .= $this->chiffrephp[$million];
            if ($million == 1) {
                $this->enLettrephp .= ' mille ';
            } else {
                $this->enLettrephp .= ' mille ';
            }
        } elseif (strlen($million) == 2) {
            $mille = substr($nombre, 2, 3);
            $nombre = substr($nombre, 0, 2);
//echo $nombre;
            $this->Dizainephp(0, $nombre, $unite, $dizaine);
            $this->enLettrephp .= 'mille ';
        } elseif (strlen($million) == 3) {
            $mille = substr($nombre, 3, 3);
            $nombre = substr($nombre, 0, 3);
            $this->Centainephp(0, $nombre, $unite, $dizaine, $centaine);
            $this->enLettrephp .= 'mille ';
        }

#recuperation des cens dans nombre

#suppression des zéros qui precederaient le $reste
        $nb = strlen($reste);
        for ($i = 0; $i <= $nb;) {
            if (substr($reste, $i, 1) == 0) {
                $reste = substr($reste, $i + 1);
                $nb = $nb - 1;
            } elseif (substr($reste, $i, 1) != 0) {
                $reste = substr($reste, $i);
                break;
            }
        }
        $nb = strlen($reste);
#si tous les chiffres apres les milions =000000 on affiche x million
        if ($nb == 0)
        ;
        else{
#Gestion des milles
            #suppression des zéros qui precederaient les milles dans $mille
            $nb = strlen($mille);
            for ($i = 0; $i <= $nb;) {
                if (substr($mille, $i, 1) == 0) {
                    $mille = substr($mille, $i + 1);
                    $nb = $nb - 1;
                } elseif (substr($mille, $i, 1) != 0) {
                    $mille = substr($mille, $i);
                    break;
                }
            }
#le nombre de caract que comporte le nombre saisi de sa forme sans espace et sans 0 au debut
            $nb = strlen($mille);
#echo '<br />nb='.$nb.'<br />';
            if ($nb == 0)
            ;
#AffichageResultat($enLettre);
            elseif ($nb == 1) {
                if ($mille == 1) {
                    $this->enLettrephp .= 'Dinar ';
                } else {
                    $this->Unitephp($mille);
                    $this->enLettrephp .= 'Dinar ';
                }
            } elseif ($nb == 2) {
                $this->Dizainephp(1, $mille, $unite, $dizaine);
                $this->enLettrephp .= 'Dinar ';
            } elseif ($nb == 3) {
                $this->Centainephp(1, $mille, $unite, $dizaine, $centaine);
                $this->enLettrephp .= 'Dinar ';
                
            }
#Gestion des cents
            #suppression des zéros qui precederaient les cents dans $cent
            $nb = strlen($cent);
            for ($i = 0; $i <= $nb;) {
                if (substr($cent, $i, 1) == 0) {
                    $cent = substr($cent, $i + 1);
                    $nb = $nb - 1;
                } elseif (substr($cent, $i, 1) != 0) {
                    $cent = substr($cent, $i);
                    break;
                }
            }
#le nombre de caract que comporte le nombre saisi de sa forme sans espace et sans 0 au debut
            $nb = strlen($cent);
#echo '<br />nb='.$nb.'<br />';
            if ($nb == 0)
            ;
#AffichageResultat($enLettre);
            elseif ($nb == 1) {
                $this->Unitephp($cent);                
            } elseif ($nb == 2) {
                $this->Dizainephp(0, $cent, $unite, $dizaine);
            } elseif ($nb == 3) {
                $this->Centainephp(0, $cent, $unite, $dizaine, $centaine);
            }

        }
    }

#Gestion des milles

    public function Millephp($nombre, $unite, $dizaine, $centaine, $mille)
    {
#si les mille comportent un seul chiffre
        #$cent represente les 3 premiers chiffres du chiffre ex: 321 dans 12321
        if (strlen($mille) == 1) {
            $cent = substr($nombre, 1);
#si ce chiffre=1
            if ($mille == 1) {
                $this->enLettrephp .= '';
            }

#si ce chiffre<>1
            elseif ($mille != 1) {
                $this->enLettrephp .= $this->chiffrephp[$mille];
            }

        } elseif (strlen($mille) > 1) {
            if (strlen($mille) == 2) {
                $cent = substr($nombre, 2);
                $nombre = substr($nombre, 0, 2);
#echo $nombre;
                $this->Dizainephp(1, $nombre, $unite, $dizaine);
            }
            if (strlen($mille) == 3) {
                $cent = substr($nombre, 3);
                $nombre = substr($nombre, 0, 3);
#echo $nombre;
                $this->Centainephp(1, $nombre, $unite, $dizaine, $centaine);
            }
        }

        $this->enLettrephp .= 'Dinar ';
#recuperation des cens dans nombre
        #suppression des zéros qui precederaient la saisie
        $nb = strlen($cent);
        for ($i = 0; $i <= $nb;) {
            if (substr($cent, $i, 1) == 0) {
                $cent = substr($cent, $i + 1);
                $nb = $nb - 1;
            } elseif (substr($cent, $i, 1) != 0) {
                $cent = substr($cent, $i);
                break;
            }
        }
#le nombre de caract que comporte le nombre saisi de sa forme sans espace et sans 0 au debut
        $nb = strlen($cent);
#echo '<br />nb='.$nb.'<br />';
        if ($nb == 0)
        ; //AffichageResultat($enLettre);
        elseif ($nb == 1) {
            $this->Unitephp($cent);
        } elseif ($nb == 2) {
            $this->Dizainephp(0, $cent, $unite, $dizaine);
        } elseif ($nb == 3) {
            $this->Centainephp(0, $cent, $unite, $dizaine, $centaine);
        }

    }

#Gestion des centaines

    public function Centainephp($inmillier, $nombre, $unite, $dizaine, $centaine)
    {

        $unite = substr($nombre, 2);
        $dizaine = substr($nombre, 1, 1);
        $centaine = substr($nombre, 0, 1);
#comme 700
        if ($unite == 0 and $dizaine == 0) {
            if ($centaine == 1) {
                $this->enLettrephp .= 'cent';
            } elseif ($centaine != 1) {
                if ($inmillier == 0) {
                    $this->enLettrephp .= ($this->chiffrephp[$centaine] . ' cents ') . ' ';
                }

                if ($inmillier == 1) {
                    $this->enLettrephp .= ($this->chiffrephp[$centaine] . ' cent ') . ' ';
                }

            }
        }
#comme 705
        elseif ($unite != 0 and $dizaine == 0) {
            if ($centaine == 1) {
                $this->enLettrephp .= ('cent ' . $this->chiffrephp[$unite]) . ' ';
            } elseif ($centaine != 1) {
                $this->enLettrephp .= ($this->chiffrephp[$centaine] . ' cent ' . $this->chiffrephp[$unite]) . ' ';
            }

        }
//comme 750
        elseif ($unite == 0 and $dizaine != 0) {
#recuperation des dizaines
            $nombre = substr($nombre, 1);
//echo '<br />nombre='.$nombre.'<br />';
            if ($centaine == 1) {
                $this->enLettrephp .= 'cent ';
                $this->Dizainephp(0, $nombre, $unite, $dizaine) . ' ';
            } elseif ($centaine != 1) {
                $this->enLettrephp .= $this->chiffrephp[$centaine] . ' cent ';
                $this->Dizainephp(0, $nombre, $unite, $dizaine) . ' ';

            }

        }
#comme 695
        elseif ($unite != 0 and $dizaine != 0) {
            $nombre = substr($nombre, 1);

            if ($centaine == 1) {
                $this->enLettrephp .= 'cent ';
                $this->Dizainephp(0, $nombre, $unite, $dizaine) . ' ';
            } elseif ($centaine != 1) {
                $this->enLettrephp .= ($this->chiffrephp[$centaine] . ' cent ');
                $this->Dizainephp(0, $nombre, $unite, $dizaine) . ' ';
            }
        }

    }

#Gestion des dizaines

    public function Dizainephp($inmillier, $nombre, $unite, $dizaine)
    {
        $unite = substr($nombre, 1);
        $dizaine = substr($nombre, 0, 1);

#comme 70
        if ($unite == 0) {
            $val = $dizaine . '0';
            $this->enLettrephp .= $this->chiffrephp[$val];
            if ($inmillier == 0 && $val == 80) {
                $this->enLettrephp .= 's ';
            }
            $this->enLettrephp .= ' ';
        }
#comme 71
        elseif ($unite != 0)
#dizaine different de 9
        {
            if ($dizaine != 9 and $dizaine != 7) {
                if ($dizaine == 1) {
                    $val = $dizaine . $unite;
                    $this->enLettrephp .= $this->chiffrephp[$val] . ' ';
                } else {
                    $val = $dizaine . '0';
                    if ($unite == 1 && $dizaine != 8) {
                        $this->enLettrephp .= ($this->chiffrephp[$val] . ' et ' . $this->chiffrephp[$unite]) . ' ';
                    } else {
                        $this->enLettrephp .= ($this->chiffrephp[$val] . '-' . $this->chiffrephp[$unite]) . ' ';
                    }
                }
            }
#dizaine =9
            elseif ($dizaine == 9) {
                $this->enLettrephp .= ($this->chiffrephp[80] . '-' . $this->chiffrephp['1' . $unite]) . ' ';
            } elseif ($dizaine == 7) {
                if ($unite == 1) {
                    $this->enLettrephp .= ($this->chiffrephp[60] . ' et ' . $this->chiffrephp['1' . $unite]) . ' ';
                } else {
                    $this->enLettrephp .= ($this->chiffrephp[60] . '-' . $this->chiffrephp['1' . $unite]) . ' ';
                }
            }
        }
       
    }
#Gestion des unites

    public function Unitephp($unite)
    {
        $this->enLettrephp .= ($this->chiffrephp[$unite])  ;
       
    }

}

