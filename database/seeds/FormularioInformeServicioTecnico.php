<?php

use Illuminate\Database\Seeder;

class FormularioInformeServicioTecnico extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_servicio_tecnico',
            'nombre_menu'=>'Informe Servicio Técnico',
            'titulo' => '',
            'creado_por'=>1,
            'tipo'=>'serv_tec'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Reporte del cliente',
            'descripcion'=>''
        ]);


        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'fecha',
            'etiqueta'=>'Fecha',
            'tipo'=>'otro',
            'icono'=>'person-outline',
            'tipo_validacion'=>'date',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>0,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteA'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'voltaje_combustible',
            'etiqueta'=>'Voltaje/Combustible',
            'tipo'=>'number',
            'icono'=>'timer-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteA'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro',
            'etiqueta'=>'Horometro',
            'tipo'=>'number',
            'icono'=>'speedometer-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>0,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteA'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'falla_reportada',
            'etiqueta'=>'Falla Reportada',
            'tipo'=>'textarea',
            'icono'=>'create-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteA'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Servicio tecnico',
            'descripcion'=>''
        ]);
        /* COLOCAR PERMISOS A PARTIR DE AHORA */

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'hora_entrada',
            'etiqueta'=>'Hora de entrada',
            'tipo'=>'otro',
            'icono'=>'time-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'h:i',
            'requerido'=>0,
            'tamano'=>'col-6',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'hora_salida',
            'etiqueta'=>'Hora de salida',
            'tipo'=>'otro',
            'icono'=>'time-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'h:i',
            'requerido'=>0,
            'tamano'=>'col-6',
            'permiso'=>'sp.parteC'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'estado',
            'etiqueta'=>'Estado',
            'tipo'=>'radio',
            'opciones'=>'D,C,F,S,G',
            'icono'=>'compass-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'tecnico_asignado',
            'etiqueta'=>'Tecnico asignado',
            'tipo'=>'text',
            'icono'=>'create-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'causa_de_danos',
            'etiqueta'=>'Causa de los daños',
            'tipo'=>'text',
            'icono'=>'list-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'trabajos_realizados',
            'etiqueta'=>'Trabajos Realizados',
            'tipo'=>'text',
            'icono'=>'list-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'cotizar',
            'etiqueta'=>'Cotizar',
            'tipo'=>'text',
            'icono'=>'list-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'comentarios',
            'etiqueta'=>'Otros / Comentarios',
            'tipo'=>'text',
            'icono'=>'list-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'sp.parteB'
        ]);
        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Firmas',
            'descripcion'=>''
        ]);
        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'firma_cliente',
            'etiqueta'=>'Firma del cliente',
            'tipo'=>'firma',
            'icono'=>'close-circle',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-6',
            'permiso'=>'sp.parteA'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'firma_tecnico',
            'etiqueta'=>'Firma del tecnico',
            'tipo'=>'firma',
            'icono'=>'list-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'',
            'requerido'=>1,
            'tamano'=>'col-6',
            'permiso'=>'sp.parteC',
            'cambio_estatus'=>1
        ]);


    }
}
