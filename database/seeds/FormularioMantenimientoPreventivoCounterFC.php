<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormularioMantenimientoPreventivoCounterFC extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_counter_fc',
            'nombre_menu'=>'Counter-FC',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
            'tipo'=>'mant_prev'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Counter-FC',
            'descripcion'=>''
        ]);

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'horometro',
            'etiqueta'=>'Horometro',
            'tipo'=>'number',
            'icono'=>'pulse-outline',
            'tipo_validacion'=>'number',
            'database_nombre'=>'nombre',
            'requerido'=>1,
            'tamano'=>'col-12',
            'permiso'=>'parteA'
        ]);

        $seeciones = ['Inspección Visual',
            'Batería',
            'Limpieza y Lubricación',
            'Bomba Hidráulica y Motor',
            'Unidad motriz',
            'Cables de alimentación y cableado de control',
            'Panel de contactores y contactores de corriente',
            'Frenos',
            'Dirección',
            'Pedal de Acelerar',
            'Sistema Hidráulico',
            'Conjunto del Mástil y Misc.',
            'Sistema de Enfriamiento.',
            'Accesorios'];

        $i=1;
        foreach ($seeciones as $sec){

            $form_sec = \App\FormularioSeccion::create([
                'formulario_id'=>$form->id,
                'titulo'=>$sec,
                'descripcion'=>''
            ]);

            switch ($i){
                case 1:
                    $campos = ['Pérdidas de Aceite','Techo de protección','Montaje de mástil','Neumaticos y Ruedas','Tracción de la rueda L.H & R.H.','Dirección de la rueda L.H & R.H.','Cadena de elevación','Limite del Interruptor Operacional (bloqueo de inclinación)',
                        'Cinturón de seguridad','Piezas dobladas o danadas','Horquillas y Trabas','Respaldo para carga','Calcomanias de seguridad y placas de capacidad en su lugar','Traba de la cubierta del asiento'];
                    break;
                case 2:
                    $campos = ['Estado de la batería','Nivel de electrolito','Agua agregada','Estado de los cables','Conector de la bateria','Condición del retenedor de baterías','Retenedor de baterías',
                        'Interruptor del sujetador de la batería','Rodillos de las baterias'];
                    break;
                case 3:
                    $campos = ['Blow off truck','Limpiar con aire a baja presión todos los paneles eléctricos','Lubricar todos los engrasadores',
                        'Lubricar el acoplamiento del freno','Lubricar Steering Botton (8 Zerks)','Lubricar el montaje del mástil y del rodillo',
                        'Lubricar las cadenas elevadoras','Lubrique los enlaces diversos','Rodillos de baterias','Lubricar los soportes del cilindro de inclinacion','Alzar/Inclinar/Aux. Palanca Asm.'];
                    break;
                case 4:
                    $campos = ['Montaje Fijo','Estado de mangueras y conectores','Fugas','Estado del inducido y de la escobilla','Limpiar con aire a presión el polvo de la escobilla del motor',
                        'Conexiones de los cables','Funcionamiento de la bomba'];
                    break;
                case 5:
                    $campos = ['Nivel del fluido','Fugas','Fijar los montajes del motor','Pernos de agarraderas de la rueda'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación','Conexiones del cable de alimentación fijas','Estado del cableado','Estado del cableado fijo'];
                    break;
                case 7:
                    $campos = ['Estado del tip de la línea','Condicion del extremo S','Condicion del extremo P (opcion levantamiento DC)'];
                    break;
                case 8:
                    $campos = ['Funcionamiento del pedal','Ajuste del interruptor de frenos','Fuga del piston de freno','Lineas de freno','Pastillas de freno',
                        'Rotores de los frenos','Interruptor del freno de estacionamiento','Ajustes del freno de estacionamiento','Fugas de la valvula de freno','Acumulador de carga & Sistema advertencia'];
                    break;
                case 9:
                    $campos = ['Funcionamiento del sistema de dirección','Unidad de control direccional','Bomba y motor fijos','Condicion del cepillo y armadura','Condicion del cilindro de direccion','Fugas en lineas hidraulicas de direccion','Steering Links',
                        'Conjunto de ejes y elementos de fijacion','Columna direccional','Columna direccional en posiccion de bloqueo'];
                    break;
                case 10:
                    $campos = ['Funcionamiento del pedal','Ajuste del interruptor'];
                    break;
                case 11:
                    $campos = ['Estado y nivel de aceite','Fugas','Tapa del respirador','Mamgueras','Filtros','Varilla/Tapa de llenado del puerto'];
                    break;
                case 12:
                    $campos = ['Rodillo y seguimiento: desgaste o daño','Condicion de la cadena de levante - desgate','Ajuste de la cadena de levante','Cilindro de inclinacion & condicion de montaje',
                        'Montaje/tornillos-Seguro Unitario de poder','Control de cables y mangueras','Limite de interruptor','Rejilla protectora de carga','Ajuste de tornillos del techo de seguridad',
                        'Ajuste del cilindro de inclinacion'];
                    break;
                case 13:
                    $campos = ['Funcionamiento del calentador','Funcionamiento del cableado'];
                    break;
                case 14:
                    $campos = ['Cuerno','Bocina','Desconeccion','Funcion potencial de direccionn','Verificar servicio de freno & freno de estacionamiento','Forward/Reverse Travel',
                        'Todo este sellado/enchufado','Velocidad lenta','Levante , Inclinar $ Funcion Aux.',
                        'Levante/Inclinar & Interruptor Funcional Aux','Directional Control Operation','Interruptores de direccionales','Capacidad estimada','Distancia de parada',
                        'Alarma de viaje','Funcion de alarma de freno','Luces trabajo','Monitor','Funcion del abanico','Compartimiento del abanico','Luces de Freno, escolta & reservas'];
                    break;
                case 15:
                    $campos = ['Funciones','Fugas','Condicion'];
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

        \App\FormularioCampo::create([
            'formulario_id'=>$form->id,
            'formulario_seccion_id'=>$form_sec->id,
            'nombre'=>'foto',
            'etiqueta'=>'Foto',
            'tipo'=>'camera',
            'icono'=>'create-outline',
            'tipo_validacion'=>'text',
            'database_nombre'=>'nombre',
            'requerido'=>0,
            'tamano'=>'col-12',
            'permiso'=>'parteA'
        ]);

    }
}
