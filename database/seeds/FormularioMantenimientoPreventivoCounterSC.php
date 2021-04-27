<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoCounterSC extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_counter_sc',
            'nombre_menu'=>'Counter-SC',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Counter-SC',
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
            'Batería',
            'Limpieza y Lubricació',
            'Bomba Hidráulica y Motor',
            'Unidad motriz',
            'Cables de alimentación y cableado de control',
            'Panel de contactores y contactores de corriente',
            'Frenos',
            'Dirección',
            'Acelerador',
            'Sistema Hidráulico',
            'Montaje del Mástil y Misc.',
            'Montacargas Utilizados en entornos frigoríficos',
            'Accionador de prueba y verificación del funcionamiento.'];

        $i=1;
        foreach ($seeciones as $sec){

            $form_sec = \App\FormularioSeccion::create([
                'formulario_id'=>$form->id,
                'titulo'=>$sec,
                'descripcion'=>''
            ]);

            switch ($i){
                case 1:
                    $campos =['Pérdidas de Aceite','Techo de protección','Montaje de mástil','Horquillas y trabas','Respaldo para carga','Estado de las ruedas Derecha','Estado de la rueda izquierda','Estado ruedas de dirección',
                        'Freno de estacionamiento','Cadena de elevación','Retenedor de baterias','Cinturón de seguridad','Traba de la cubierta del asiento','Calcomanias de seguridad y placas de capacidad en su lugar'];
                    break;
                case 2:
                    $campos = $campos = ['Estado de la batería','Nivel de electrolito','Agua agregada','Estado de los cables','Estado del respaldo de carga','Retenedor de baterías','Condición del retenedor de baterías',
                        'Todos los conectores del arnés','Rodillos de las baterias'];
                    break;
                case 3:
                   $campos = ['Limpiar la montacarga con aire a presión','Limpiar con aire a baja presión todos los paneles eléctricos','Luricar todos los engrasadores',
                        'Lubricar el acoplamiento del freno','Montaje de la palanca aux. de elevación/ inclinación','Lubricar el montaje del mástil y del rodillo',
                        'Lubricar las cadenas elevadoras'];
                    break;
                case 4:
                    $campos =$campos = ['Montaje Fijo','Estato de mangueras y conectores','Fugas1','Estado del inducido y de la escobilla','Limpiar con aire a presión el polvo de la escobilla del motor',
                        'Conexiones de los cables','Funcionamiento de la bomba'];
                    break;
                case 5:
                    $campos = $campos = ['Nivel del fluido','Fugas2','Fijar los montajes del motor','Estado del inducido y de la escobilla','Pernos de agarraderas de la rueda',
                        'Limpiar con aire a presión el polvo de la escobilla del motor'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación','Conexiones del cable de alimentación fijas','Estado del cableado','Estado del cableado fijo'];
                    break;
                case 7:
                    $campos = ['Estado del tip de la bomba','Estato del tip de la línea'];
                    break;
                case 8:
                    $campos = ['Funcionamiento del pedal','Ajuste del interruptor de frenos','Ajuste Freno Derecho','Ajuste freno izquierdo','Pastilla de freno',
                        'Rotores del freno derecho','Rotores del freno izquierdo','Ajustes del freno de estacionamiento','Interruptor del freno de estacionamiento'];
                    break;
                case 9:
                    $campos = ['Funcionamiento del sistema de dirección','Unidad de control direccional','Bomba y motor fijos','Columna de dirección','Posición del bloqueo de la columna de dirección',
                        'Estado del inducido y de la escobilla','Ajuste','Potenciómetro de dirección','Pernos de agarraderas del volante'];
                    break;
                case 10:
                    $campos = ['Funcionamiento del pdeal','Funcionamiento del cableado'];
                    break;
                case 11:
                    $campos = ['Estado y nivel de aceite','Fugas3','Tapa del respiradero','Mamgueras y conectores','Filtros'];
                    break;
                case 12:
                    $campos = ['Rodillo y rastreo: desgaste o daño','Estado de la cadena de elevación: desgaste','Ajuste de la cadena de elevación','Cables y mangueras del mástil','Interruptor limitador',
                        'Cilindro de inclinación y elevación y estado del soporte','Perno para fijar el montaje a la unidad motriz','Pernos ajustados del techo de protección'];
                    break;
                case 13:
                    $campos = ['Funcionamiento de la resistencia','Funcionamiento del cableado'];
                    break;
                case 14:
                    $campos = ['Indicador de descarga','Bocina','Funcionamiento de sistema de dirección','Interruptor de frenos','Verificar el funcionamiento de los frenos','Distancia de parada (a)',
                        'Frenado de contramarcha (b)','Funcionamiento del sistema auxiliar de inclinación y elevación','Interruptores del sistema auxiliar de inclinación y elevación',
                        'Velocidad progresiva (b)','Interruptores direccionales','Capacidad indicada','Registro de fallas: módulo de control maestro y auxiliar','control adicional',
                        'Alarma de desplazamiento','Luces traseras','Luces de trabajo'];
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
                    'tamano' => 'col-12',
                    'permiso'=>'parteA'
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
            'requerido'=>0,
            'tamano'=>'col-12',
            'permiso'=>'parteB',
            'cambio_estatus'=>1,
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
            'tamano'=>'col-12',
            'permiso'=>'parteA'
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
            'tamano'=>'col-12',
            'permiso'=>'parteA'
        ]);

    }
}
