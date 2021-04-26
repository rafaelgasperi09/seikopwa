<?php

namespace App;

use App\Http\Traits\FilterDataTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioRegistro extends BaseModel
{
    use SoftDeletes;
    protected $table = 'formulario_registro';
    protected $guarded = ['id','typeheadA'];
    protected $creator_field_name = 'creado_por';

    public function creador(){
        return $this->belongsTo('App\User','creado_por');
    }

    public function formulario(){
        return $this->belongsTo('App\Formulario','formulario_id');
    }

    public function data(){
        return $this->hasMany(FormularioData::class);
    }

    public function cliente(){
        return Cliente::find($this->cliente_id);
    }

    public function equipo(){
        return Equipo::find($this->equipo_id);
    }

    public function savePdf()
    {
        $formularioRegistro = FormularioRegistro::find($this->id);
        $formulario = Formulario::find($formularioRegistro->formulario_id);
        $equipo = Equipo::find($formularioRegistro->equipo_id);
        $solicitud = MontacargaSolicitud::findOrFail($formularioRegistro->solicitud_id);
        $consecutivo = $solicitud->consecutivo_exportable;
        $horometro_campo = $formulario->campos()->whereNombre('horometro')->first();
        $horometo = $formularioRegistro->data()->whereFormularioCampoId($horometro_campo->id)->first()->valor;

        $width = 297;
        $height = 420;
        $pageLayout = array($width, $height);
        $pdf = new TCPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);
        $pdf->SetConfigInforme();
        $pdf->AddPage();

        $pdf->cabeceraInformeMPreventivo($formularioRegistro->formulario->nombre_menu, $formularioRegistro->created_at,$consecutivo);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetLineStyle($pdf->borderDashed());

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + 3);
        $pdf->Cell(20, 6, "CLIENTE", 0, 0, 'L');
        $pdf->Rect($x, $y + 3, 20, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $clinete = $solicitud->clientes ? $solicitud->clientes->nombre : "";
        $pdf->Cell(50, 6,  html_entity_decode($clinete), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $pdf->Cell(20, 6, "EQUIPO", 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 20, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);

        $pdf->Cell(50, 6,  html_entity_decode($equipo->numero_parte), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $tipo_equipo = $equipo->subTipo ? $equipo->subTipo->display_name : "";
        $pdf->Cell(50, 6,  html_entity_decode($tipo_equipo), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $horometros = $horometo ? $horometo : "";
        $pdf->Cell(60, 6, html_entity_decode("Horometro: " . $horometros), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 60, 6, 'D', array('all' => $pdf->borderDashed()));
        $pdf->Ln();

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + 3);
        $pdf->Cell(20, 6, "Marca", 0, 0, 'L');
        $pdf->Rect($x, $y + 3, 20, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $marca = $equipo->marca ? $equipo->marca->display_name : "";
        $pdf->Cell(50, 6,  html_entity_decode($marca), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $pdf->Cell(20, 6, "Modelo", 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 20, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        //$modelo = $solicitud->equipos ? $solicitud->equipos->modelo : "";
        $pdf->Cell(50, 6,  html_entity_decode($equipo->modelo), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $pdf->Cell(50, 6, "Serie", 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderDashed()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        //$serie = $solicitud->equipos ? $solicitud->equipos->serie : "";
        $pdf->Cell(60, 6,  html_entity_decode($equipo->serie), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 60, 6, 'D', array('all' => $pdf->borderDashed()));
        $pdf->Ln();

        $index = 0;
        //$tabInformes = TabInforme::where('exportable', $numero)->get();
        $secciones = $formulario->secciones()->whereNotIn('titulo',['Counter-SC','Counter-FC','Counter-RC','Pallet-PE','RR - RD SERIE 52','COMBUSTION','STOCK PICKER','Informacion Adicional'])->get();
        $cantidad_secciones = $secciones->count();
        //$cantidad_tab = $tabInformes->count();
        $isInforme = 0;
        $contar_tab = 0;
        $informes_solicitud = null;

        /*if ($solicitud->informes->count() > 0) {
            $informes_solicitud = $solicitud->informes;
            $isInforme = 1;
        }*/

        if ($formularioRegistro->data()->get()->count() > 0) {
            $formulario_data = $formularioRegistro->data()->get();
            $isInforme = 1;
        }

        // valores iniciales
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $sw_a = 0;
        $max_y_posicion_ = 0;
        $_y = 0;
        $posicion_x_inicial = 90;

        foreach($secciones as $i => $seccion) {
            if ($index > 4 && $y > 295) {
                // Modificamos la bandera
                $sw_a = 1;

                // Obtenemos la posicion Y
                $max_y_posicion_ = $y;

                // Aumentamos a $posicion_x_inicial la posicion X
                $x = $x + $posicion_x_inicial;

                $pdf->SetY(65);

                $y = $pdf->GetY();
            }

            $size = $pdf->getSizeFont(5);//$numero

            $pdf->SetFont('helvetica', 'P', $size);
            $pdf->SetXY($x, $y);
            $pdf->SetLineStyle($pdf->borderSolid());
            $pdf->SetTextColor(255, 255, 255);
            $pdf->MultiCell(85, 5, html_entity_decode($seccion->titulo), 1, 'L', 1, false);
            $pdf->Ln();

            if ($size > 8) {
                $pdf->SetTextColor(73, 73, 73);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $pdf->SetFont('helvetica', 'p', $size);

            //$cantidad_informe = $tabInforme->informes->count();
            $cantidad_campos= $seccion->campos()->get()->count();

            foreach($seccion->campos()->get() as $j => $campo) {
                // Valores por defectos con la primera
                // modificacion del $index > 4 && $y > 295
                if ($sw_a == 1) {
                    $_y = 70;
                    $sw_a = 0;
                } else {
                    $_y = $pdf->GetY();
                }

                // Verificamos que la posicion Y actual
                // no pase los 320 de hacerlo se reinician
                // las posiciones a la indicadas
                if ($_y > 300) {
                    $x = $x + $posicion_x_inicial;
                    $_y = 65;
                }

                $pdf->SetXY($x, $_y);

                $sigla = "";

                if ($isInforme == 1) {
                    /* foreach ($informes_solicitud as $informe_marcado) {
                         if (($informe_marcado->id == $informe->id) && ($informe_marcado->pivot)) {
                             $pivot = $informe_marcado->pivot;
                             $evaluacion_trabajo = $informe_marcado->evaluacion_trabajos()
                                 ->where('id', $pivot->evaluacion_id)
                                 ->first();

                             $sigla = $evaluacion_trabajo->sigla;
                         }
                     }*/

                    foreach ($formulario_data as $data){

                    }

                    $sigla = $formularioRegistro->data()->whereFormularioCampoId($campo->id)->first()->valor;
                }

                $pdf->MultiCell(10, 4,$sigla , 'T', 'C', false, 0);
                $pdf->MultiCell(75, 4,  html_entity_decode($campo->etiqueta), 1, 'L', 0, true);
                $pdf->Ln(0);

                if ($j == $cantidad_campos - 1) {
                    $pdf->Ln(5);
                }
            }

            $y = $pdf->GetY();

            if (($index > 4) && ($y > 300)) {
                $x = $x + $posicion_x_inicial;
                $y = 65;
                $pdf->SetY($y);
            }

            // Modificacion del indice
            $index = $index + 1;
        }

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(50, 50, 50);

        $x = $pdf->GetX();
        $y = 310;
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "Trabajo Recibido por: ", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 6, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 14, 'D', array('all' => $pdf->borderSolid()));

        $x = $pdf->GetX();
        $x = $x + 5;
        $y = $pdf->GetY();
        $y = $y - 6;
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "Trabajo Realizado por: ", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 6, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $y = $y + 6;
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 14, 'D', array('all' => $pdf->borderSolid()));

        $x = $pdf->GetX();
        $x = $x + 5;
        $y = $y - 6;
        $pdf->SetXY($x, $y);
        $pdf->MultiCell(85, 20, "C = Correcto\nA = Ajustar\nR = Reparar\nU = Urgente", 0, 'C');
        $pdf->Rect($x, $y, 85, 20, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $x = $pdf->GetX();
        $y = $y + 25;
        $pdf->SetXY($x, $y);
        $pdf->MultiCell(85, 6, "OBSERVACION:", 0, 'L');
        $pdf->Rect($x, $y, 85, 6, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $w = $pdf->getPageWidth() ;
        $pdf->SetXY($x, $y - 6);
        $pdf->MultiCell(265, 30, $solicitud->descripcion, 1, 'L');
        $pdf->Ln();

        $pdf->SetFont('helvetica', 'B', 14);
        $x = $pdf->GetX();
        $pdf->SetXY($x, -40);
        $pdf->Cell(265, 6, "Trabajos bien hecho al precio correcto", 0, 0, 'C');

        $name = 'mant_prev_frm_reg_'.$formularioRegistro->id.'.pdf';
        $path = storage_path('app/public/pdf/'.$name);

        $pdf->Output($path, 'F');

        return $path;
    }
}
