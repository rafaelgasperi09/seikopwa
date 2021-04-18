<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoCounterRC extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_counter_rc',
            'nombre_menu'=>'Counter-RC',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Counter-RC',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro',
            'etiqueta'=>'Horometro',
            'tipo'=>'number',
            'icono'=>'pulse-outline',
            'tipo_validacion'=>'fecha',
            'database_nombre'=>'nombre',
            'formato_fecha'=>'Y-m-d',
            'requerido'=>1,
            'tamano'=>'col-12'
        ]);

        $seeciones = ['Inspección Visual',
            'Batería y Cables',
            'Limpieza y Lubricación',
            'Bomba Hidráulica y Motor',
            'Unidad motriz',
            'Cables de alimentación y cableado de control',
            'Contactores de la bomba y de la línea',
            'Dirección',
            'Sistema Hidráulico',
            'Montaje del mástil',
            'Entornos frigoríficos',
            'Accionador de prueba y verificación del funcionamiento'];

        $i=1;
        foreach ($seeciones as $sec){

            $form_sec = \App\FormularioSeccion::create([
                'formulario_id'=>$form->id,
                'titulo'=>$sec,
                'descripcion'=>''
            ]);

            switch ($i){
                case 1:
                    $campos = ['Pérdidas de Aceite','Pernos del techo de protección y del montaje','Montaje del mástil','Desplazador lateral (si hay)','Horquillas y trabas','Respaldo para carga','Almoadillas del compartimiento del operador','Equipamiento de montaje y de la 3a columna',
                        'Pedal de freno','Piso','Timón de dirección','Conexiones del cable de alimentación','Estado de las ruedas','Rueda de tracción (izquierda)','Rueda de tracción (derecha)',
                    'Ruedas de dirección','Cadenas elevadoras y anclajes','Mangueras','Cables de control','Timón de control multifunciones','Piezas dobladas,rotas o dañadas','Etiquetas/calcomanías de seguridad,placas de capacidad y de datos en su lugar','Suspensión del piso'];
                    break;
                case 2:
                    $campos = ['Estado de la batería','Nivel de electrolito','Agua agregada','Retenedores de baterías','Estado de los cables','Estado del conector de la batería','Separadores de la batería','Rodillos de las batería (limpieza)'];
                    break;
                case 3:
                    $campos = ['Limpiar el montacarga con aire a presión','Limpiar con aire a baja presión todos los paneles eléctricos y el ventilador del controlador','Luricar los engrasadores',
                        'Timón del control multifunciones','Canal y rodillo del mástil','Pedal de Freno','Cadenas elevadoras','Accesorios'];
                    break;
                case 4:
                    $campos = ['Montaje Fijo','Estado del inducido y de la escobilla (solo montacargas de DC)','Limpiar con aire a presión el polvo de la escobilla del motor (solo montacargas de DC)','Funcionamiento de la bomba'];
                    break;
                case 5:
                    $campos = ['Nivel del Aceite','Perdidas de fluido','Tuercas de agarraderas de la rueda'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación','Conexiones fijas del cable de alimentación','Conectores y conexiones del cableado','Estado del cableado'];
                    break;
                case 7:
                    $campos = ['Estato del tip del contactor de línea','Estato del tip del contactor de la bomba'];
                    break;
                case 8:
                    $campos = ['Unidad de control direccional fija','Estado de las mangueras y los conectores','Fugas'];
                    break;
                case 9:
                    $campos = ['Nivel del fluido hidráulico','Fugas','Tapas del respiradero','Estado de la mangueras y los conectores','Filtro','Filtro de aspiración'];
                    break;
                case 10:
                    $campos = ['Rendimiento y rastreo:desgaste o daño','Estado de la cadena de levante:desgaste','Ajuste de la cadena de levante','Controlar el ajuste de los pernos en los bloques del pivote del mástil',
                    'Interruotor de proxímidad','Cable de control (interruptor de proxímidad)','Topes/bumpers'];
                    break;
                case 11:
                    $campos = ['Funcionamiento del calentador','Estado del cableado','Conexiones del cableado'];
                    break;
                case 12:
                    $campos = ['Bocina','Pantalla','Desconexión de emergencia eléctrica','Sistema de servo alimentación','Interruptores de retenedor de la batería (opcional)',
                        'Funcionamiento del sistema de freno','Distancia de parada (a)','Frenado por contramarcha (b)','Aplicar/liberar el freno de estacionamiento','Funcionamiento de inclinción y elevación',
                        'Funcionamiento del sistema accesorio','Velocidad progresiva/máxima','Proxímidad de la altura del mástil , funcionamiento del interruptor','Timón de control multifunciones','Capacidad indicada',
                        'Luces de trabajo','Luces estroboscópicas','Interruptores de ka barra de entrada','Pedal','Suspensión del piso','Apoyabrazos ajustable (opcional)'];
                    break;
            }

            foreach ($campos as $cam) {
                $nombre = strtolower(str_replace(' ', '_', $cam));
                \App\FormularioCampo::create([
                    'formulario_id' => $form->id,
                    'formulario_seccion_id' => $form_sec->id,
                    'nombre' => Str::slug($nombre.'_'.$form_sec->id, '_'),
                    'etiqueta' => $cam,
                    'tipo' => 'radio',
                    'opciones' => 'C,A,R,U',
                    'icono' => 'checkmark-outline',
                    'tipo_validacion' => 'radio',
                    'database_nombre' => 'nombre',
                    'requerido' => 1,
                    'tamano' => 'col-12'
                ]);
            }
            $i++;
        }

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Informacion Adicional',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'trabajo_recibido_por',
            'etiqueta'=>'Trabajo Recibido por',
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
            'nombre'=>'trabajo_realizado_por',
            'etiqueta'=>'Trabajo Realizado por',
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
            'nombre'=>'observacion',
            'etiqueta'=>'Observación',
            'tipo'=>'textarea',
            'icono'=>'create-outline',
            'tipo_validacion'=>'text',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12'
        ]);

    }
}
