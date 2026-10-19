<?php

setlocale(LC_TIME, "fr_FR");
ini_set("display_errors", 1);

require "config/fpdf/fpdf.php";
 
header('Content-Type: text/html; charset=utf-8');
 
include('../config.menuqrcode/config.php'); 
    
$object = new application(); 
 
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

//Select Invoice Details From Database
$object->query = "SELECT 
                    c.reference,
                    c.name,
                    c.email,
                    c.tel,
                    c.num_table,
                    m.market ,
                    m.address,
                    m.tel as market_tel,
                    c.created,
                    m.logo
                FROM
                    commande c
                    LEFT JOIN market m ON m.id = c.market
                WHERE
                    c.id =  '" . $_POST['id'] . "' ";

$result = $object->get_result();
  
//nom and invoice details
$info = array();

foreach ($result as $row) {
    $info['logo'] =  str_replace('../app.menuqrcode.tn/','',$row['logo']) ; 
    $info['reference'] =  $row['reference'] ; 
    $info['name'] =  $row['name'] ; 
    $info['email'] =  $row['email'] ; 
    $info['tel'] =  $row['tel'] ; 
    $info['num_table'] =  $row['num_table'] ; 
    $info['market'] =  $row['market'] ; 
    $info['address'] =  $row['address'] ; 
    $info['market_tel'] =  $row['market_tel'] ;  
    $info['date'] =  date('d/m/Y',strtotime($row['created']));
    $info['time'] =  date('H:i:s',strtotime($row['created']));
    $info['total'] = number_format($object->total_commande($_POST['id']),3,',',' '); 
}


//Select Invoice Product Details From Database
$object->query = "  SELECT
                        l.*,
                        a.libelle
                    FROM
                        commande_ligne l
                    LEFT JOIN article a ON
                        a.id = l.article
                    WHERE
                        l.commande =  '" . $_POST['id'] . "' ";

$result = $object->get_result();

$products_info = array();

foreach ($result as $row) {
    
    $sub_data = array(); 
    $sub_data['quantite'] = $row['quantite'];
    $sub_data['article'] = utf8_decode(ucwords($row['libelle'])); 
    $sub_data['prix'] = number_format( ($row['prix_unitaire'] * $row['quantite'] ),3,',',' ') ;

    $products_info[] = $sub_data;
}

class PDF extends FPDF
{
    public $widths;
    public $aligns;

    private $ordonnee;
    private $abscisse;

    public function Header()
    {

    }

    public function SetWidths($w)
    {
        //Tableau des largeurs de colonnes
        $this->widths = $w;
    }

    public function SetAligns($a)
    {
        //Tableau des alignements de colonnes
        $this->aligns = $a;
    }

    public function Row($data, $bool)
    {
        //Calcule la hauteur de la ligne
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = 7 * $nb;
        //Effectue un saut de page si nécessaire
        // $this->CheckPageBreak($h);
        //Dessine les cellules

        $length = count($data);

        for ($i = 0; $i < count($data); $i++) {
            $w =  $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';

            //Sauve la position courante
            $x = $this->GetX();
            $y = $this->GetY();
            //Dessine le cadre

            if ($bool) {
                $this->Rect($x, $y, $w, $h, 'F');
            }

            //Imprime le texte

            switch ($i) {
                case 0:
                    $this->MultiCell($w, 6, $data[$i], 0, 'L');
                    // $this->Cell(5, 7, $data[$i], 0,  "L"); 
                    break;
                case 1:
                    $this->MultiCell($w, 6, $data[$i], 0, 'L');
                    break;
                case 2:
                    $this->MultiCell($w, 6, $data[$i], 0, 'R');
                    break; 
            }

            //Repositionne à droite
            $this->SetXY($x + $w, $y);

            $this->ordonnee = $y;

        }
        //Va à la ligne
        $this->Ln($h);

    }

    // public function CheckPageBreak($h)
    // {
    //     //Si la hauteur h provoque un débordement, saut de page manuel
    //     if ($this->GetY() + $h > $this->PageBreakTrigger) {
    //         $this->AddPage($this->CurOrientation);
    //     }

    // }

    public function NbLines($w, $txt)
    {
        //Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }

            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }

                } else {
                    $i = $sep + 1;
                }

                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }

        }
        return $nl;
    }

    public function body($info, $products_info)
    {
        $this->SetFont("Arial", "B", 1);
        $cellWidth = 80;
        $imageWidth = 20;
        
        // Calculate the X position for centering the image
        $xPos = ($cellWidth - $imageWidth) / 2;
        
        // Add the image centered within the cell
        $this->Cell($cellWidth, 13, '', 0, 0, "C");
        $this->Image('img/icon-menuqrcode.png', $xPos, $this->GetY(), $imageWidth);
        
        $y  = 32;  
        $this->SetY($y);
        $this->SetX(0);
        $this->SetFont('Times', 'B', 22); 
        $this->MultiCell(80, 7, utf8_decode($info['market']), 0, "C");
        
        $y+=15;
        $this->SetY($y);
        $this->SetX(0);
        $this->SetFont('Times', '', 13); 
        $this->MultiCell(80, 7,  utf8_decode($info["address"]), 0, 'C');
         
        
        $y+=7;
        $this->SetY($y);
        $this->SetX(0);
        $this->SetFont('Times', '', 13); 
        $this->Cell(80, 7, utf8_decode("Tél : (+216) ".$info['market_tel'] ), 0, 1,"C"); 
        
        $y+=7;
        $this->SetY($y);
        $this->SetX(0);
        $this->SetFont('Times', 'B', 12); 
        $this->Cell(80, 7, utf8_decode("Réference : ".$info['reference'] ), 0, 1,"C"); 
        
        $y+=7;
        $this->SetY($y);
        $this->SetX(10);
        $this->SetFont('Times', '', 12); 
        $this->Cell(25, 7, utf8_decode("Table : ".$info['num_table'] ),"", 0, "L"); 
        $this->Cell(30, 7, utf8_decode( $info['date']." ".$info['time'] ),"", 0, "L"); 

        
        $y+=7;
        $this->SetY($y);
        $this->SetX(0);
        $this->SetFont('Times', '', 12); 
        $this->Cell(80, 7, utf8_decode("Client : ".$info['name'] ), 0, 1,"C"); 
        
        $y+=7;
        $this->SetY($y);
        $this->SetX(10);
        $this->SetFont('Arial', '', 10);   
        $this->Cell(60, 2,  "------------------------------------------------------", "", 1, "C" );
  
        $y+=2;
        $this->SetY($y);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 7, "Qte", 'B', 0, "L"); 
        $this->Cell(30, 7, "Article", 'B', 0, "L"); 
        $this->Cell(20, 7, "Montant", 'B', 1, "R"); 

        //Table de 3 colonnes
        $this->SetFont('Arial', '', 9); 
          
        $this->SetWidths(array(10,30,20));

        $colored = false;

        foreach ($products_info as $row) {
            $this->Row(
                array( 
                    number_format($row["quantite"], 0, ",", " "), 
                    $row["article"],
                    number_format($row["prix"], 2, ",", " ") . " TND",
                ), $colored); 
        }
        
        $this->Ln(5);
        
        // total HT row
        $this->SetFont('Arial', 'B', 13);  
        $this->Cell(10, 7, "TOTAL", "", 0, "L" );
        $this->Cell(50, 7, number_format($info['total'], 2, ",", " ") . ' TND', "", 1, "R" );
        
        $this->SetFont('Arial', '', 10);   
        $this->Cell(60, 4,  "------------------------------------------------------", "", 1, "C" );
  
        $this->SetFont('Arial', '', 13);   
        $this->Cell(60, 7,  "Merci pour votre visite", "", 1, "C" );
        
        $this->SetFont('Arial', 'B', 13);   
        $this->Cell(60, 7,  "Menu QR Code", "", 1, "C" );
        
        $this->Ln(5);
    }

    public function Footer()
    {
    }

}

 
//Create A4 Page with Portrait
$pdf = new PDF("P", "mm", array(200,80));

$pdf->SetTitle('Ticket');
$pdf->SetAuthor('Menu QR Code');
$pdf->SetSubject('Ticket');
$pdf->SetCreator('Menu QR Code');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->body($info, $products_info);

// $pdf->Output();
$pdf->Output('I', 'ticket.pdf');
