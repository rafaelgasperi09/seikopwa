<?php

use Illuminate\Database\Seeder;

class FormularioDailyCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_daily_check',
            'nombre_menu'=>'Daily Check',
            'titulo' => 'Control diario de operador de montacargas',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Chequeo Diario Montacarga',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'semana',
            'etiqueta'=>'Semana',
            'tipo'=>'date',
            'icono'=>'calendar-outline',
            'tipo_validacion'=>'fecha',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'dia_semana',
            'etiqueta'=>'Dia de la Semana',
            'tipo'=>'date',
            'icono'=>'calendar-outline',
            'tipo_validacion'=>'fecha',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Chequeos Visuales',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'identificacion',
            'etiqueta'=>'Identificación del equipo',
            'subetiqueta'=>'(modelo,serie,datos)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'seguridad',
            'etiqueta'=>'Equipos de Seguridad',
            'subetiqueta'=>'(Harmés de seguridad y Harmés de vida) SP',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'danos_estructura',
            'etiqueta'=>'Daños de la estructura',
            'subetiqueta'=>'(piezas dobladas o rotas)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'fugas',
            'etiqueta'=>'Fugas',
            'subetiqueta'=>'(aceite, liq bateria, otros)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'ruedas',
            'etiqueta'=>'Ruedas',
            'subetiqueta'=>'(condición)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horquillas',
            'etiqueta'=>'Horquillas',
            'subetiqueta'=>'(en su lugar, niveladas, desgastadas con seguro)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'cadenas_cables_mangueras',
            'etiqueta'=>'Cadenas, Cables y Mangueras',
            'subetiqueta'=>'(en su lugar)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'bateria',
            'etiqueta'=>'Bateria',
            'subetiqueta'=>'(Indicaciones nivel de agua, tapas ventiladoras en su lugar  limpieza)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'conector_bateria',
            'etiqueta'=>'Conector de Bateria',
            'subetiqueta'=>'(agrietado, quemado, apretado)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'protectores',
            'etiqueta'=>'Protectores',
            'subetiqueta'=>'(parrilla, respaldo de carga, retenedor de bateria)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'dispositivos_seguridad',
            'etiqueta'=>'Dispositivos de Seguridad',
            'subetiqueta'=>'(luces, cinturon de seguridad, etiquetas de peligro, vidrio frontal)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'control_handle',
            'etiqueta'=>'Control Handle',
            'subetiqueta'=>'(estado)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'extintor',
            'etiqueta'=>'Extintor',
            'subetiqueta'=>'',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Chequeso Operacionales',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro',
            'etiqueta'=>'Horometro',
            'subetiqueta'=>'(trabajando)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'pito',
            'etiqueta'=>'Pito',
            'subetiqueta'=>'(suena)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'direccion',
            'etiqueta'=>'Direccion',
            'subetiqueta'=>'(ningun atascamiento, ningun juego excesivo)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'control_traccion',
            'etiqueta'=>'Control de Traccion',
            'subetiqueta'=>'(Rangos de velocidad para adelante y reversa, ningun ruido inusual)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'control_hidraulicos',
            'etiqueta'=>'Control Hidraulicos',
            'subetiqueta'=>'(mov. de levantar, bajar, inclinar, extension, desplazador derecho e izquierdo, ningun ruido inusual)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'frenos',
            'etiqueta'=>'Frenos',
            'subetiqueta'=>'(detiene la unidad dentro de la distancia requerida, trabajan suavemente, funciones de sobrepaso del freno)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'freno',
            'etiqueta'=>'Freno',
            'subetiqueta'=>'(asiento, mano, pie y parking)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'carga_bateria',
            'etiqueta'=>'Carga de bateria',
            'subetiqueta'=>'(medidor de descarga funcionando correctamente)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'indicador_descarga_bateria',
            'etiqueta'=>'Indicador de descarga de la bateria',
            'subetiqueta'=>'(funcionamineto)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'desconector_poder',
            'etiqueta'=>'Desconector de poder',
            'subetiqueta'=>'(corta todo el poder electrico)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'luces_alarma_retroceso',
            'etiqueta'=>'Luces y Alarmas de retroceso',
            'subetiqueta'=>'(funcionamiento)',
            'tipo'=>'radio',
            'opciones'=>'OK,M,R',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'check',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Info Adicional',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'lectura_horometro',
            'etiqueta'=>'Lectura del Horometro',
            'subetiqueta'=>'',
            'tipo'=>'number',
            'icono'=>'checkmark-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'operador',
            'etiqueta'=>'Operador (Nombre o Firma)',
            'subetiqueta'=>'',
            'tipo'=>'firma',
            'icono'=>'pencil-outline',
            'tipo_validacion'=>'firma',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'ok_supervidor',
            'etiqueta'=>'Ok del Supervisor (Nombre o Firma)',
            'subetiqueta'=>'',
            'tipo'=>'firma',
            'icono'=>'pencil-outline',
            'tipo_validacion'=>'firma',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'comentarios',
            'etiqueta'=>'Comentarios',
            'subetiqueta'=>'(articulos que necesitan reparacion o ajuste)',
            'tipo'=>'textarea',
            'icono'=>'create-outline',
            'tipo_validacion'=>'text',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'foto',
            'etiqueta'=>'Foto',
            'subetiqueta'=>'(foto instantanea del equipo)',
            'tipo'=>'file',
            'icono'=>'camera-outline',
            'tipo_validacion'=>'file',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12',
            'deleted_at'=>now()
        ]);


    }
}
