<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TCPDF extends \TCPDF
{
    public function __construct($orientacion, $unit, $format, $bool, $codificacion, $bool2)
    {
        parent::__construct($orientacion, $unit, $format, $bool, $codificacion, $bool2);
    }

    public function borderDashed()
    {
        return [
            'width' => 1,
            'cap' => 'round',
            'join' => 'round',
            'dash' =>'2,10',
            'color' => array(153, 153, 153)
        ];
    }

    public function borderSolid()
    {
        return [
            'width' => 0.5,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(153, 153, 153)
        ];
    }

    public function SetConfigInforme()
    {
        $this->SetLineWidth(0.4);
        $this->SetCreator('GMPAPP');
        $this->SetFont('helvetica', '', 12);
        $this->SetAuthor('Rafael Gasperi');
        $this->SetTitle('Montacargas y Respuestos, S.A.');
        $this->SetSubject('Informe de servicio tecnico');
        $this->SetKeywords('TCPDF, PDF, php, html5, css3, javascrip, jquery');

        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->SetDisplayMode('real','default');
    }

    public function SetConfig()
    {
        $this->SetLineWidth(0.4);
        $this->SetCreator('Angel Hidalgo');
        $this->SetFont('helvetica', '', 12);
        $this->SetAuthor('GMP');
        $this->SetTitle('Montacargas y Respuestos, S.A.');
        $this->SetSubject('Daily Check Report');
        $this->SetKeywords('TCPDF, PDF, php, html5, css3, javascrip, jquery');

        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
       // $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->SetDisplayMode('real','default');
    }

    public function cabeceraServicioTecnico()
    {
        $this->SetFont('helvetica', 'B', 18);
        $this->SetXY(100, 24);
        $this->Cell(100, 6, html_entity_decode('Montacargas y Repuestos, S.A.'), 0, 'C', 0);

        $this->SetFont('helvetica', '', 12);
        $this->SetXY(100, 32);
        $this->Cell(100, 6, html_entity_decode("Trabajo bien hecho al precio correcto siempre."), 0, 'C', 0);

        $this->SetFont('helvetica', 'B', 15);
        $this->SetXY(112, 38);
        $this->Cell(75, 6, html_entity_decode("Informe de servicio Técnico."), 0, 'C', 0);
    }

    public function cabeceraInformeMPreventivo($nombre, $fecha, $consecutivo)
    {
        $this->SetLineStyle($this->borderSolid());
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Rect($x, $y, 200, 18, 'D', array('all' => $this->borderSolid()));
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(200, 0, html_entity_decode("SERVICIO INDUSTRIAL DE MONTACARGAS, S.A"), 0, 0, 'C');
        $this->Ln();

        $this->SetTextColor(217, 83, 79);
        $this->SetFont('helvetica', 'P', 10);
        $this->Cell(30, 0, "No. " . html_entity_decode($consecutivo), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'P', 11);
        $this->Cell(140, 0, html_entity_decode("Tel/Fax 236-8079, E-mail: gmp@montacargaspanama.com"), 0, 0, 'C');
        $this->Ln();

        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(200, 0, html_entity_decode("INFORME DE MANTENIMIENTO PREVENTIVO"), 0, 0, 'C');

        $this->SetFont('helvetica', 'B', 10);
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(153, 153, 153);
        $x = $this->GetX() + 5;
        $this->SetXY($x, $y);
        $this->Cell(60, 10, html_entity_decode($nombre), 0, 0, 'C');
        $this->Rect($x, $y, 60, 10, 'D', array('all' => $this->borderDashed()));
        $this->Ln();

        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(153, 153, 153);
        $y = $this->GetY();
        $this->SetXY($x, $y);
        $this->Cell(60, 10, html_entity_decode("Fecha: " . $fecha), 0, 0, 'L');
        $this->Ln();
    }

    public function Header()
    {
        //
    }

    /**
     * Overwrite Footer() method.
     * @public
     */
    public function Footer()
    {
        if ($this->tocpage) {
            // *** replace the following parent::Footer() with your code for TOC page
            parent::Footer();
        } else {
            // *** replace the following parent::Footer() with your code for normal pages
            parent::Footer();
        }
    }

    public function getSizeFont($numero_exportable)
    {
        switch ($numero_exportable) {
            case 1:
                return 8.5;
                break;

            case 2:
                return 9.2;
                break;

            case 3:
                return 10.8;
                break;

            case 4:
                return 9.5;
                break;

            case 5:
                return 9.8;
                break;

            case 6:
                return 8;
                break;

            case 7:
                return 8;
                break;

            default:
                return 8;
                break;
        }
    }
}
