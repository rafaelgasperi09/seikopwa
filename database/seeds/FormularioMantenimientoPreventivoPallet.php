<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoPallet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_pallet',
            'nombre_menu'=>'Pallet-PE',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
            'tipo'=>'mant_prev'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'Pallet-PE',
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
            'Batería y Cables',
            'Limpieza y Lubricación',
            'Sistema Hidráulico',
            'Unidad motriz',
            'Cables de alimentación',
            'Interruptores y cableado',
            'Panel de Contactores',
            'Frenos',
            'Dirección',
            'Montacargas utilizados en entornos frigoríficos',
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
                    $campos = ['Fugas de aceite','Brazo y timón de control','Barra de sujeción','Horquillas','Respaldo para carga','Amortiguador de baterías','Rueda de carga (derechas e izquierdas)','Ajuste y ruedas caster',
                        'Rueda de tracción y permos de agarraderas','Partes dobladas o dañadas','Etiquetas/calcomanías de seguridad placa de serie en su lugar','Cilindro de elevación','Acoplamiento de elevación',
                        'Adaptador de la barra estabilizadora (opc.)'];
                    break;
                case 2:
                    $campos = ['Estado de la batería','Nivel de electrolito','Agua agregada','Estado del Retenedor de baterías','Estado de los cables','Estado del conector de la batería','Separadores de la batería','Estado el timón de control para desconectar la batería (opc.)'];
                    break;
                case 3:
                    $campos = ['Limpiar el equipo con aire a presión','Limpiar con aire a baja presión el panel eléctrico','Luricar todos los engrasadores',
                        'Pivote de la unidad motriz','Acoplamiento de elevación superior','Pivote del cilindro de elevación superior','Rodamientos de la unidad motriz',
                        'Eje de la unidad motriz','Ruedas caster y mecanismo','Acoplamiento de elevación inferiror','Barras de tensión','Elevadores','Pivote y eje de la rueda de carga','Rodamientos de salida',
                        'Lubricar el acoplamiento del freno','Lubricar los rodamientos de las baterías (opc.)','Lubricar las visagras de la puerta','Lubricar lo pivotes del timón de control',
                        'Acoplamiento del selector de movimiento a rueda libree, yugo cojinetes y resortes de los rodamientos'];
                    break;
                case 4:
                    $campos = ['Niel de fluido','Montaje Fijo del motor y la bomba','Conexiones de los cables del motor','Inducido y escobillas del motor (PE 4000 únicamente)','Funcionamiento de la bomba',
                        'Mangueras y conectores','Fugas del cilndro y de la unidad hidráulica','Tapa del respiradero'];
                    break;
                case 5:
                    $campos = ['Nivel del lubricante','Fugas','Montaje fijo del motor','Conexiones de los cables del motor','Inducido y escobilla','Limpiar con aire a presión el polvo de la escobilla del motor (PE 4000 únicamente)',
                        'Montaje fijo a la unidad motriz','Proteción fija del motor','Rodamientos y aro de rodamientos','Quitar la cinta, el resorte, etc que rodean al eje'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación','Conexiones del cable de alimentación','Conexiones del resistidor'];
                    break;
                case 7:
                    $campos = ['Estato del Cableado','Conexiones del cableado','Conectores de arnés','Interruptores de control en el timón de control','Interruptor de reversa',
                        'Interruptor limitador de elevación','Interruptores de control en la barra de sujeción','Selenoide de la bomba','Interruptores del retenedor de las baterías','Interruptor de frenos'];
                    break;
                case 8:
                    $campos = ['Estado del tip del contactor de línea'];
                    break;
                case 9:
                    $campos = ['Acoplamiento','Ajuste (a)','Zapatas','Tambores'];
                    break;
                case 10:
                    $campos = ['Rodamiento de la unidad motriz'];
                    break;
                case 11:
                    $campos = ['Funcionamiento de la resistencia','Estado del cableado'];
                    break;
                case 12:
                    $campos = ['Bocina','Desconexión de emergencia eléctrica','Interruptor de frenos','Verificar el funcionamiento de los frenos','Distancia de parada (a)',
                        'Frenado por contramarcha (b)','Operación de elevación y descanso','Velocidades de desplazamiento','Funcionamiento del interruptor limitador de elevación',
                        'Funcionamiento del interruptor direccional','Funcionamiento del bóton de reversa','Operación manual del movimiento a rueda libre','Funcionamiento del interruptor de interbloqueo del retenedor de baterías (opc.)',
                        'Funcionamiento del resplado para carga','Alarma de desplazamiento (opc.)','Verificar el funcionamiento del freno de estacionamiento','Funcionamiento del QuickPick',
                        'Indicadores audibles y visuales de QuicCoast','Funcionamiento del QuickCoast','Pantalla del operador y luces indicadoras'];
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
