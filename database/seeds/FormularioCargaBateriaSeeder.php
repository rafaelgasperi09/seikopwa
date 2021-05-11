<?php

use Illuminate\Database\Seeder;

class FormularioCargaBateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_bat_control_carga',
            'nombre_menu'=>'Control de Carga',
            'titulo' => 'Control de carga en cuarto de baterias',
            'creado_por'=>1,
            'tipo'=>'carga_bateria'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Control de carga',
            'descripcion'=>'Todas las columnas referentes a la fecha y hora y % de carga tanto de entrada como de salida del cuarto de carga.'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'accion',
            'etiqueta'=>'Accion',
            'tipo'=>'select',
            'opciones'=>'entrada,salida',
            'icono'=>'calendar-outline',
            'tipo_validacion'=>'fecha',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'fecha',
            'etiqueta'=>'fecha',
            'tipo'=>'date',
            'icono'=>'calendar-outline',
            'tipo_validacion'=>'fecha',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>1,
            'tamano'=>'col-6'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'hora_entrada',
            'etiqueta'=>'Horario de Entreda',
            'tipo'=>'time',
            'icono'=>'hourglass-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-6'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro_salida_cuarto',
            'etiqueta'=>'Horometro salida del cuarto',
            'tipo'=>'number',
            'icono'=>'pulse-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>0
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'carga_salida_cuarto',
            'etiqueta'=>'%Carga salida del cuarto',
            'tipo'=>'number',
            'icono'=>'battery-charging-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>0
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro_entrada_cuarto',
            'etiqueta'=>'Horometro entrada del cuarto',
            'tipo'=>'number',
            'icono'=>'pulse-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>0
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'carga_entrada_cuarto',
            'etiqueta'=>'%Carga entrada del cuarto',
            'tipo'=>'number',
            'icono'=>'battery-charging-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>0
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horas_uso_bateria',
            'etiqueta'=>'Horario de uso de la bateria',
            'tipo'=>'text',
            'icono'=>'hourglass-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'h2o',
            'etiqueta'=>'H2O',
            'tipo'=>'checkbox',
            'icono'=>'water-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-6'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'ecu',
            'etiqueta'=>'ECU',
            'tipo'=>'checkbox',
            'icono'=>'git-pull-request-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-6'
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'observacion',
            'etiqueta'=>'Observacion',
            'tipo'=>'textarea',
            'icono'=>'document-text-outline',
            'tipo_validacion'=>'texto',
            'database_nombre'=>'nombre',
            'requerido'=>0
        ]);


    }
}
