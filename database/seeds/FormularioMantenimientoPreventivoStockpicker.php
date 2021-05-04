<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoStockpicker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_stock_picker',
            'nombre_menu'=>'STOCK PICKER',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'STOCK PICKER',
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
            'Bomba(s) y Motor(es) Hidráulico(s)',
            'Unidad motriz y Unidad de tracción',
            'Cables de alimentación y cableado de control',
            'Contactores y reles',
            'Frenos Eléctricos',
            'Sistema de dirección eléctrica',
            'Sistema Hidráulico',
            'Montaje del Mástil',
            'Montacargas para entornos frigoríficos',
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
                    $campos = ['Fugas de aceite',
                        'Techo de protección',
                        'Horquillas',
                        'Protector de seguridad',
                        'Montaje de la plataforma y del mástil',
                        'Interruptores limitadores',
                        'Pedal del freno',
                        'Volante',
                        'Sensor de altura',
                        'Panel de control',
                        'Sensores con cable guía (opcional)',
                        'Estado de las ruedas',
                        'Rueda de tracción',
                        'Ruedas de carga (derechas e izquierdas)',
                        'Ruedas guía para pasillos derechas e izquierdas (opcional)',
                        'Cadena de levante y yugo',
                        'Arnés del operador (cinturón y soga) Extintor (opcional)',
                        'Mecanismo de sujeción de carga (opcional)',
                        'Aplicar y liberar',
                        'Estado de los cables',
                        'Estado de los resortes',
                        'Calcomanías de seguridad y placas de datos en su lugar',
                        'Sujetadores ajustados y en su lugar'];
                    break;
                case 2:
                    $campos = ['Estado de la batería',
                        'Nivel de electrolito',
                        'Agua agregada',
                        'Retenedor de baterías',
                        'Estado del retenedor de baterías',
                        'Estado de los cables',
                        'Estado del respaldo de carga'];
                    break;
                case 3:
                    $campos = ['Limpiar el montacargas con aire a presión',
                        'Limpiar con airea baja presión todos lospaneles eléctricos',
                        'Lubricar todos los conectores de l•uRburiecdaacsiódne carga',
                        'Ruedas guía para pasillos (opcional) • Pivote de la unidad motriz',
                        'Engranajes de dirección',
                        'Rodamientos de la columna y del canal del mástil',
                        'Deslizaderas del pedal del freno',
                        'Resortes del amortiguador',
                        'Plataforma',
                        'Mástil',
                        'Cadena de levante',
                        'Bisagras de la puerta',
                        'Unidad motriz de la caja de dirección',
                        'Rodamientos de la batería',
                        'Aplicar Armorall o protector transparente a los cables y las mangueras del mástil'];
                    break;
                case 4:
                    $campos = ['Montaje fijo',
                        'Conexiones de cables',
                        'Mangueras y conectores',
                        'Fugas',
                        'Inducidos y escobillas',
                        'Limpiar con aire a presión el polvo de la escobilla de los motores',
                        'Funcionamiento de la bomba'];
                    break;
                case 5:
                    $campos = ['Nivel del lubricante',
                        'Fugas',
                        'Montaje fijo del motor',
                        'Conexiones de los cables del motor',
                        'Inducido y escobilla',
                        'Limpiar con aire a presión el polvo de la escobilla del motor',
                        'Montaje fijo a la unidad motriz',
                        'Pernos de agarraderas de las ruedas de tracción',
                        'Funcionamiento de la bomba de la unidad motriz'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación',
                        'Conexiones del cable de alimentación',
                        'Estado del cableado',
                        'Conexiones del cableado',
                        'Conectores del arnés',
                        'Estado del arnés'];
                    break;
                case 7:
                    $campos = ['Estado del contacto y del relé K1',
                        'Estado del contacto y del relé K2',
                        'Estado del contacto y del relé K5',
                        'Estado del contacto y del relé ED1',
                        'Estado del contacto y del relé ED2',
                        'Estado del contacto y del relé P1',
                        'Estado del contacto y del relé P2',
                        'Estado del contacto y del relé K11(F/C)',
                        'Estado del contacto y del relé K12 (F/C)'	];
                    break;
                case 8:
                    $campos = ['Funcionamiento del pedal del freno',
                        'Ajuste del interruptor de frenos',
                        'Ajuste de frenos (a)',
                        'Pastillas de freno',
                        'Rotor de los frenos'];
                    break;
                case 9:
                    $campos = ['Estado de ECR1',
                        'Estado y ajuste de ECR2',
                        'Montaje del motor de dirección',
                        'Estado delinducido y de laescobilla',
                        'Limpiar con aire a presión el polvo del motor',
                        'Ajuste del encoder del cable guía'];
                    break;
                case 10:
                    $campos = ['Estado y nivel del aceite',
                        'Fugas',
                        'Tapa del respiradero',
                        'Mangueras y conectores',
                        'Filtros',
                        'Válvula de alivio y solenoide'];
                    break;
                case 11:
                    $campos = ['Rodamiento y rastreo: desgaste o daño',
                        'Interruptores de ajuste de tensión de la cadena',
                        'Topes y bumpers múltiples',
                        'Estado de las cadenas de levante: desgaste',
                        'Ajustes de la cadena de levante Interruptores limitadores',
                        'Estado del soporte y del cilindro',
                        'Fugas del cilindro',
                        'Pernos del montaje fijo a la unidad motriz Cables de control',
                        'Tensión del cable de control Poleas',
                        'Yugo'];
                    break;
                case 12:
                    $campos = ['Funcionamiento de la resistencia (circuito)',
                        'Estado del cableado'];
                    break;
                case 13:
                    $campos = ['Panel de visualización',
                        'Bocina',
                        'Desconexión eléctrica',
                        'Operación de elevación y descenso',
                        'Funcionamiento de la dirección eléctrica',
                        'Funcionamiento del freno',
                        'Distancia de parada del freno (a)',
                        'Funcionamiento de la tracción ECR3',
                        'Distancia de frenado por contramarcha (c)',
                        'Funcionamiento de las puertas laterales derecha e izquierda',
                        'Alarma de desplazamiento (opcional)',
                        'Velocidades de desplazamiento',
                        'Funcionamiento de la altura de ECR4 (opcional)',
                        'Funcionamiento de la dirección del ECR1',
                        'Funcionamiento de la dirección del ECR2',
                        'Funcionamiento del interruptor direccional',
                        'Funcionamiento de los interruptores limitadores del mástil (opcional)',
                        'Ajustes del montacargas',
                        'Capacidad indicada',
                        'Selección de zona (opcional)',
                        'Funcionamiento del cable guía (opcional)',
                        '7 luces y ventilador',
                        'Luz intermitente'];
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
