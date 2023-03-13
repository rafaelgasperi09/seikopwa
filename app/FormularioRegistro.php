<?php

namespace App;

use App\Http\Traits\FilterDataTrait;
use App\Notifications\NewMantenimientoPreventivo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;
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

    public function ult_horometro(){
        return $this-> equipo()->ult_horometro();
    }

    public function tecnicoAsignado(){
        return $this->belongsTo(User::class,'tecnico_asignado');
    }

    public function estatusHistory(){
        return $this->hasMany(FormularioRegistroEstatus::class);
    }

    public function solicitud(){
        return MontacargaSolicitud::find($this->solicitud_id);
    }

    public function createSolicitudMontacarga(){

        if($this->estatus =='C'){

            $horometro ='';
            $obs ='';
            $horometroCampo = $this->formulario()->first()->campos()->where('nombre','horometro')->first();
            $horoData = $this->data()->whereFormularioCampoId($horometroCampo->id)->first();
            if($horoData){
                $horometro = $horoData->valor;
            }

            $obsCampo = $this->formulario()->first()->campos()->where('nombre','observacion')->first();
            $obsData = $this->data()->whereFormularioCampoId($obsCampo->id)->first();
            if($obsData){
                $obs = $obsData->valor;
            }

            // crear una solicitud de mantenimiento preventivo en la base de dato de montacarga
            $equipo = Equipo::find($this->equipo_id);
            $solicitud = new MontacargaSolicitud();
            $consecutivo = MontacargaConsecutivo::where('consecutivo_opcion','mantenimiento-preventivo')->first();
            $next_values_consecutivo = $consecutivo->numero_consecutivo+1;
            $solicitud->cliente_id = $equipo->cliente_id;
            $solicitud->tipo_servicio_id = 3; //mantenimiento-preventivo
            $solicitud->equipo_id = $equipo->id;
            $solicitud->usuario_creado_id = 1; // crear un app_user debe ser  el usuario actual pero tendriamos que cazarlo con uno de la bd de montacarga
            $solicitud->usuario_id = 1; //
            $solicitud->departamento_id =9; // servicio-tecnico
            $solicitud->horometro = $horometro;
            $solicitud->estado_id = 1; // abierta
            $solicitud->descripcion = $obs;
            $solicitud->consecutivo_exportable = $next_values_consecutivo;

            if($solicitud->save()){

                $consecutivo->numero_consecutivo = $next_values_consecutivo;
                $consecutivo->save();
                // salvar la copia
                $copia_sol =new MontacargaCopiaSolicitud();
                $copia_sol->fill($solicitud->toArray());
                $copia_sol->usuario_creado_id = 1; // crear un app_user
                $copia_sol->usuario_id = 1;
                $copia_sol->nombre_servicio = 'Mantenimiento Preventivo';
                $copia_sol->nombre_contacto = $equipo->cliente->nombre;
                $copia_sol->nombre_departamento = 'Servicio técnico';
                $copia_sol->nombre_estado = 'Abierto';
                $copia_sol->nombre_usuario_crea = current_user()->getFullName();
                $copia_sol->equipo = $equipo->numero_parte;
                $copia_sol->save();
                // creams el pdf de la solicitud
                $pdf = $this->savePdf($solicitud);

                MontacargaImagen::create([
                    'name' =>$pdf['url'],
                    'directory'=>'app/public/pdf',
                    'solicitud_id'=>$solicitud->id,
                    'calidad'=>'original',
                    'usuario_id'=>1,
                ]);

                $this->solicitud_id = $solicitud->id;
                $this->nombre_archivo = $pdf['url'];

                // enviar notificacion al o los supervisores gmp
                $notificados = User::FilterByRoles([5])->get();
                $when = now()->addMinutes(1);
                foreach ($notificados as $noti){
                    $when = now()->addMinutes(1);
                    if(current_user()->id <> $noti->id)
                        $noti->notify((new NewMantenimientoPreventivo($this))->delay($when));
                }

                FormularioRegistro::withoutEvents(function (){
                    return $this->save();
                });
            }

        }
    }

    public function savePdf($solicitud,$uploadFile=true)
    {
        //$formularioRegistro = FormularioRegistro::find($this->id);
        $equipo = Equipo::find($this->equipo_id);
        $formulario = Formulario::find($this->formulario_id);
        $consecutivo = $solicitud->consecutivo_exportable;
        $horometro = $solicitud->horometro;
        $observacion = $solicitud->descripcion;
        $width = 297;
        $y_max_pos = 300;
        if(in_array($equipo->tipo->name,['stock-picker']))  {
            $y_max_pos = 343;
        }elseif(in_array($equipo->tipo->name,['reach'])){
            $y_max_pos = 343;
        }elseif(in_array($equipo->tipo->name,['counter-sc'])){
            $y_max_pos = 300;
        }elseif(in_array($equipo->tipo->name,['we/ws','wp','wv'])){
            $y_max_pos = 253;
        }elseif(in_array($equipo->tipo->name,['pallet-pe'])){
            $y_max_pos = 310;
        }elseif(!empty($equipo->tipo_motore_id)){
            $y_max_pos = 343;
        }
        $height = $y_max_pos+100;
        $pageLayout = array($width, $height);
        $pdf = new TCPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);
        $pdf->SetConfigInforme();
        $pdf->AddPage();

        $pdf->cabeceraInformeMPreventivo($this->formulario->nombre_menu, $this->created_at,$consecutivo);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetLineStyle($pdf->borderDashed());

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + 3);
        $pdf->Cell(20, 6, "CLIENTE", 0, 0, 'L');
        $pdf->Rect($x, $y + 3, 20, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $clinete = $solicitud->cliente ? $solicitud->cliente->nombre : "";
        $pdf->Cell(50, 6,  html_entity_decode($clinete), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $pdf->Cell(20, 6, "EQUIPO", 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 20, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);

        $pdf->Cell(50, 6,  html_entity_decode($equipo->numero_parte), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $tipo_equipo = $equipo->subTipo ? $equipo->subTipo->display_name : "";
        $pdf->Cell(50, 6,  html_entity_decode($tipo_equipo), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $horometros = $horometro ? $horometro : "";
        $pdf->Cell(60, 6, html_entity_decode("HOROMETRO: " . $horometros), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 60, 6, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + 3);
        $pdf->Cell(20, 6, "MARCA", 0, 0, 'L');
        $pdf->Rect($x, $y + 3, 20, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $marca = $equipo->marca ? $equipo->marca->display_name : "";
        $pdf->Cell(50, 6,  html_entity_decode($marca), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        $pdf->Cell(20, 6, "MODELO", 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 20, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        //$modelo = $solicitud->equipos ? $solicitud->equipos->modelo : "";
        $pdf->Cell(50, 6,  html_entity_decode($equipo->modelo), 0, 0, 'L');
        $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));

        $pdf->SetTextColor(153, 153, 153);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);

        if(!empty($equipo->tipo_motore_id)) {
            $pdf->Cell(17, 6, "SERIE", 0, 0, 'L');
            $pdf->Rect($x + 3, $y, 17, 6, 'D', array('all' => $pdf->borderSolid()));
        }else{
            $pdf->Cell(50, 6, "SERIE", 0, 0, 'L');
            $pdf->Rect($x + 3, $y, 50, 6, 'D', array('all' => $pdf->borderSolid()));
        }

        $pdf->SetTextColor(76, 76, 76);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x + 3, $y);
        //$serie = $solicitud->equipos ? $solicitud->equipos->serie : "";
        if(!empty($equipo->tipo_motore_id)) {
            $pdf->Cell(32, 6,  html_entity_decode($equipo->serie), 0, 0, 'L');
            $pdf->Rect($x + 3, $y, 30, 6, 'D', array('all' => $pdf->borderSolid()));
        }else{
            $pdf->Cell(60, 6,  html_entity_decode($equipo->serie), 0, 0, 'L');
            $pdf->Rect($x + 3, $y, 60, 6, 'D', array('all' => $pdf->borderSolid()));
        }



        if(!empty($equipo->tipo_motore_id)){
            $pdf->SetTextColor(153, 153, 153);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 6, "MOTOR", 0, 0, 'L');
            $pdf->Rect($x , $y, 20, 6, 'D', array('all' => $pdf->borderSolid()));
            $pdf->SetTextColor(76, 76, 76);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x + 3, $y);
            $pdf->Cell(32, 6,  html_entity_decode($equipo->motor->display_name), 0, 0, 'L');
            $pdf->Rect($x + 3, $y, 38, 6, 'D', array('all' => $pdf->borderSolid()));
            //motor
        }
        $pdf->Ln();

        $index = 0;
        //$tabInformes = TabInforme::where('exportable', $numero)->get();
        $secciones = $formulario->secciones()->whereNotIn('titulo',['Counter-SC','Counter-FC','Counter-RC','Pallet-PE','RR - RD SERIE 52','COMBUSTION','STOCK PICKER','WAVE/STACKER/WALKE-PALLET'])->get();
        $cantidad_secciones = $secciones->count();
        //$cantidad_tab = $tabInformes->count();
        $isInforme = 0;
        $contar_tab = 0;
        $informes_solicitud = null;

        /*if ($solicitud->informes->count() > 0) {
            $informes_solicitud = $solicitud->informes;
            $isInforme = 1;
        }*/

        if ($this->data()->get()->count() > 0) {
            $formulario_data = $this->data()->get();
            $isInforme = 1;
        }

        // valores iniciales
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $sw_a = 0;
        $max_y_posicion_ = 0;

        $_y = 0;
        $posicion_x_inicial = 90;
        $firmasPath=array();
        $nombreTecnico="";
        foreach($secciones as $i => $seccion) {
            if($seccion->titulo=='Informacion Adicional'){
                foreach($seccion->campos()->get() as $campo) {
                    if( $campo->tipo=='firma' && $this->data()->whereFormularioCampoId($campo->id)->first()){

                        if($campo->nombre=='trabajo_recibido_por'){
                            $firmasPath[1] =  storage_path('/app/public/firmas/'.$this->data()->whereFormularioCampoId($campo->id)->first()->valor);
                        }

                        if($campo->nombre=='trabajo_realizado_por'){
                            $firmasPath[2] =  storage_path('/app/public/firmas/'.$this->data()->whereFormularioCampoId($campo->id)->first()->valor);
                            $nombreTecnico=$this->data()->whereFormularioCampoId($campo->id)->first()->user_id;
                            $nombreTecnico=User::find( $nombreTecnico)->full_name;
                          
                        }
                    }
                }

            }else{
                    if ($index > 4 && $y > $y_max_pos-5) {
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
                        if ($_y > $y_max_pos) {
                            $x = $x + $posicion_x_inicial;
                            $_y = 65;
                        }

                        $pdf->SetXY($x, $_y);

                        $sigla = "";

                        if ($isInforme == 1) {
                            $sigla = $this->data()->whereFormularioCampoId($campo->id)->first()->valor;
                        }

                        $pdf->MultiCell(10, 4,$sigla , 'T', 'C', false, 0);
                        $pdf->MultiCell(75, 4,  html_entity_decode($campo->etiqueta), 1, 'L', 0, true);
                        $pdf->Ln(0);

                        if ($j == $cantidad_campos - 1) {
                            $pdf->Ln(5);
                        }


                    }


                    $y = $pdf->GetY();

                    if (($index > 4) && ($y > $y_max_pos)) {
                        $x = $x + $posicion_x_inicial;
                        $y = 65;
                        $pdf->SetY($y);
                    }

                    // Modificacion del indice
                    $index = $index + 1;
            }
        }

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(50, 50, 50);

        $x = $pdf->GetX();
        $y = $y_max_pos+10;
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "Trabajo Recibido por: ", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 6, 'D', array('all' => $pdf->borderSolid()));
        $pdf->Ln();

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->Cell(85, 6, "", 0, 0, 'L');
        $pdf->Rect($x, $y, 85, 14, 'D', array('all' => $pdf->borderSolid()));

        $firma1['x']=$x;
        $firma1['y']=$y;

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

        $firma2['x']=$x;
        $firma2['y']=$y;

        $x = $pdf->GetX();
        $x = $x + 5;
        $y = $y - 6;
        $pdf->SetXY($x-20, $y);
        $pdf->MultiCell(85, 20, "C = Correcto\nA = Ajustar  \nR = Reparar\nU = Urgente", 0, 'C');
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
        //$pdf->Cell(265, 6, "Trabajos bien hecho al precio correcto", 0, 0, 'C');
        if(isset($firmasPath[1])){
            $pdf->SetXY($firma1['x']+18, $firma1['y']);
            $pdf->Image($firmasPath[1],  '', '', 50, 14, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
        }

        if(isset($firmasPath[2])){
            $pdf->SetXY($firma2['x']+18, $firma2['y']);
            $pdf->Image($firmasPath[2],  '', '', 50, 14, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $pdf->SetFont('helvetica', 'P', 10);
            $pdf->SetXY(33, -90);
            $pdf->Cell(265, 6,$nombreTecnico, 0, 0, 'C');
        }

        $pdf->SetFont('helvetica', 'B', 20);
        $x = $pdf->GetX()/2;
        $pdf->SetXY(16, -40);
        $pdf->Cell(265, 6, '"Satisfacción y confianza"', 0, 0, 'C');

        if($uploadFile){
            $name = 'mant_prev_frm_reg_'.$this->id.'.pdf';
            $path = storage_path('app/public/pdf/'.$name);

            $pdf->Output($path, 'F');

            return  ['path'=>$path,'url'=>'pdf/'.$name];
        }else{
            return $pdf;
        }

    }

    public function savePdfDC()
    {
        $formularioRegistro = FormularioRegistro::find($this->id);
        $formulario = Formulario::find($formularioRegistro->formulario_id);
        $equipo = Equipo::find($formularioRegistro->equipo_id);
        $horometro_campo = $formulario->campos()->whereNombre('horometro')->first();
        $horometo = '';
        if($formularioRegistro->data()->whereFormularioCampoId($horometro_campo->id)->first()) $horometo = $formularioRegistro->data()->whereFormularioCampoId($horometro_campo->id)->first()->valor;
        $datos=FormularioRegistro::where('ano',$formularioRegistro->ano)->where('semana',$formularioRegistro->semana)->get();
        $data=array();
       //$dias=array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $dias=array('Lunes1','Lunes2','Martes1','Martes2','Miercoles1','Miercoles2','Jueves1','Jueves2','Viernes1','Viernes2','Sabado1','Sabado2');
       $dataQuery="SELECT
       fr.semana,fr.ano,fd.formulario_campo_id,fc.nombre,fc.tipo,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Lunes1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Lunes1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Lunes2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Lunes2,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Martes1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Martes1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Martes2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Martes2,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Miercoles1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Miercoles1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Miercoles2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Miercoles2,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Jueves1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Jueves1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Jueves2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Jueves2,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Viernes1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Viernes1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Viernes2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Viernes2,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Sabado1' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Sabado1,
                MAX(CASE CONCAT(fr.dia_semana,fr.`turno_chequeo_diario`) WHEN 'Sabado2' THEN CONCAT(fd.valor,'|',fd.user_id) ELSE '' END) AS Sabado2
                FROM formulario_registro fr,formulario_data fd,formulario_campos fc
                WHERE fr.id=fd.formulario_registro_id
                AND fd.formulario_campo_id=fc.id
                AND fr.semana=$formularioRegistro->semana
                AND fr.ano=$formularioRegistro->ano
                GROUP BY fr.semana,fr.ano,fd.formulario_campo_id,fc.nombre,fc.tipo ";

        $data=\DB::select(DB::Raw($dataQuery));
       
        $users=\DB::select(DB::Raw("SELECT id,CONCAT(IFNULL(first_name,''),' ',IFNULL(last_name,'')) AS name FROM users"));
        $users = array_column($users,'name','id');

        $width = 220;
        $height = 380;
        $pageLayout = array($width, $height);

        $pdf = new TCPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);

        $pdf->SetConfig();

        $pdf->AddPage();

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x-12, $y-10);

        $pdf->Image(public_path('images/dce3.png'),  1, 1, 220, 340, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
        $name = 'daily_check-'.$formularioRegistro->id.'.svg';
        $path = storage_path('app/public/pdf/'.$name);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(50, 50, 50);

        $x = 20;
        $y = 15;
        $pdf->SetXY($x, $y);
        $pdf->Cell(55, 6, $formularioRegistro->equipo()->cliente->nombre, 0, 0, 'L');
        $x = $pdf->GetX()+17;
        $pdf->SetXY($x, $y);
        $pdf->Cell(30, 6, $formularioRegistro->equipo()->cliente->direccion, 0, 0, 'L');
        $x = $pdf->GetX()+38;
        $pdf->SetXY($x, $y);
        $date = \Carbon\Carbon::now();
        $date->setISODate($formularioRegistro->ano,$formularioRegistro->semana);

        $pdf->Cell(30, 6, $date->startOfWeek()->format('Y-m-d'), 0, 0, 'L');
        $x = $pdf->GetX()+9;
        $pdf->SetXY($x, $y);
        $pdf->Cell(6, 6, "21 ", 0, 0, 'L');
        $x = 20;
        $y = 19;
        $pdf->SetXY($x, $y);
        $pdf->Cell(40, 8,$formularioRegistro->equipo()->marca()->first()->display_name, 0, 0, 'L');
        $y = $pdf->GetY()+5;
        $pdf->SetXY($x, $y);
        $pdf->Cell(40, 8, $formularioRegistro->equipo()->modelo, 0, 0, 'L');
        $x = $pdf->GetX()+35;
        $pdf->SetX($x);
        $pdf->Cell(30, 6, $formularioRegistro->equipo()->serie, 0, 0, 'L');
        $pdf->SetXY(160,30);
        $pdf->Cell(10, 6, $formularioRegistro->equipo()->numero_parte, 0, 0, 'L');
        $matrizx=array(79,89,101,111,122,
                       132,143,155,166,175,
                       186,199  );
        $matrizy=array(53,61,67,74,79,
                      86,93,100,107,113,
                      121,128,134,140,147,
                      154,161,168,178,186,
                      192,198,206,212,255,276);

        $vars=array(
        'prioridad'=>45,    
        'identificacion'=>53,
        'seguridad'=>61,
        'danos_estructura'=>67,
        'fugas'=>74,
        'ruedas'=>79,
        'horquillas'=>86,
        'cadenas_cables_mangueras'=>93,
        'bateria'=>100,
        'conector_bateria'=>107,
        'protectores'=>113,
        'dispositivos_seguridad'=>121,
        'control_handle'=>128,
        'extintor'=>134,
        'horometro'=>140,
        'pito'=>147,
        'direccion'=>154,
        'control_traccion'=>161,
        'control_hidraulicos'=>168,
        'frenos'=>178,
        'freno'=>186,
        'carga_bateria'=>192,
        'nivel_carga_bateria'=>198,
        'indicador_descarga_bateria'=>204,
        'desconector_poder'=>211,
        'luces_alarma_retroceso'=>218,
        'lectura_horometro'=>240,
        'operador'=>261,
        'ok_supervisor'=>281);
       $comentarios='';$contador=0;

       foreach($data as $d){

            $datos=json_decode(json_encode($d), true);
            foreach($matrizx as $xkey=>$vx){
                if($datos["nombre"]=='comentarios'){
                    if($datos[$dias[$xkey]]<>''){
                        $comentarios.=$datos[$dias[$xkey]].' |';
                        $contador++;
                        if($contador==2){
                            $contador=0;
                            $comentarios.='|';
                        }
                    }

                }
                
                if(isset($vars[$datos["nombre"]])){
                    $valor=$datos[$dias[$xkey]];
                    $valor=explode('|',$valor);
                    
                    if($datos["nombre"]=='operador'){
                        
                        if(isset($users[end($valor)]))
                            $valor=substr($users[end($valor)],0,12);
                        else   {
                            $valor="";
                        } 
                           
                    }
                    else
                        $valor=$valor[0];

                    $pdf->SetXY($vx,$vars[$datos["nombre"]]);
                    
                    if(in_array($datos["nombre"],['operador','ok_supervisor','lectura_horometro'])){
                           
                        if($datos["nombre"]=='ok_supervisor'){
                            $pdf->StartTransform();
                            $pdf->Rotate(90);
                            $pdf->Image(storage_path('app/public/firmas/'.$valor),  '', '', 20, 10, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
                        }else{
                            $size = $pdf->getSizeFont(4);//$numero
                            $pdf->SetFont('helvetica', 'B', $size);
                            $pdf->SetXY($vx-4,$vars[$datos["nombre"]]);
                            $pdf->StartTransform();
                            $pdf->Rotate(70);
                            $pdf->Cell(2, 6, $valor, 0, 0, 'L');
                        }
                        
                        $pdf->StopTransform();
                    }else{
                       if($valor=='M' or $valor=='R')
                        $pdf->SetTextColor(254, 0, 0);
                       else
                        $pdf->SetTextColor(0, 0, 0,100);
                        $pdf->Cell(2, 6, $valor, 0, 0, 'L');

                    }
                }
            }

       }

       $comentarios= explode('||',$comentarios);
       foreach($comentarios as $key=>$c){
            $pdf->SetXY(10,278+($key*5));
            $pdf->Cell(200, 10, $c, 0, 0, 'L');
       }



        $pdf->Output($path, 'F');

        return $path;
    }

}
