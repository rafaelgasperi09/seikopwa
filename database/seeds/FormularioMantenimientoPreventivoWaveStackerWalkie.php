<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoWaveStackerWalkie extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_wave_stacker_walke',
            'nombre_menu'=>'Wave/Stacker/Walke Pallet',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'WAVE/STACKER/WALKE-PALLET',
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
            'Sistema Hidráulico(s)',
            'Unidad motriz',
            'Cables de alimentación y cableado de control',
            'Panel de contactores y contactores de energia',
            'Controlador de Traccion',
            'Manija de Control',
            'Frenos',
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
                            'Respaldo de carga (opcional)',
                            'Horquillas',
                            'Interruptor de límite',
                            'Brazo de control y manija',
                            'Estado de los neumáticos y las ruedas',
                            'Pernos de llanta y orejeta de tracción',
                            'Rueda de carga derecha',
                            'Rueda de carga izq.',
                            'Rueda derecha (opcional)',
                            'Rueda izquierda (opcional)',
                            'Placa protectora derecha',
                            'Placa protectora izquierda',
                            'Calcomanías de seguridad y placas de capacidad en su lugar'];
                    break;
                case 2:
                    $campos = ['Estado de la batería',
                        'Nivel de electrolitos',
                        'Agua añadida',
                        'Condición del cable',
                        'Condición del receptáculo',
                        'Condición de la manija de desconexión de la batería'];
                    break;
                case 3:
                    $campos = ['Camión de descarga',
                        'Lubricar',
                        'Cojinetes de ruedas de carga',
                        'Elevación de varillaje',
                        'Elevadores',
                        'Ruedas giratorias',
                        'Muelles helicoidales de la manija de control',
                        'Base y parte superior del cilindro de elevación',
                        'Bisagra del paquete de baterías',
                        'Cojinete de dirección',
                        'Aplique Armorall o Clear Guard a las mangueras' ];
                    break;
                case 4:
                    $campos = ['Montaje seguro de la bomba y el motor',
                        'Nivel y condición del aceite',
                        'Estado de las mangueras y las conexiones',
                        'Fugas-Cilindro, etc.',
                        'Tapa de respiradero',
                        'Filtros de succión y retorno',
                        'Condición del cepillo y la armadura',
                        'Polvo de cepillo de soplado del motor' ];
                    break;
                case 5:
                    $campos = ['Nivel de fluido',
                        'Fugas',
                        'Montaje seguro del motor',
                        'Condición del cepillo y la armadura',
                        'Montaje seguro en la unidad de potencia',
                        'Sople el polvo del cepillo del motor',
                        'Cojinetes de dirección',
                        'Quite la cinta, la cuerda, etc. de alrededor del eje.',
                        'Pernos de orejeta de la rueda motriz'];
                    break;
                case 6:
                    $campos = ['Condiciones del cable de alimentación',
                        'Conexiones seguras del cable de alimentación',
                        'Condiciones de cableado',
                        'Conexiones de cableado seguras',
                        'Condición del arnés de cableado'];
                    break;
                case 7:
                    $campos = ['Condición de la punta del contactor de línea',
                         'Condición de la punta del contactor de la bomba'];
                    break;
                case 8:
                    $campos = ['Velocidad lenta (a)',
                        'Taponamiento (a)',
                        'Panel de limpieza de polvo'];
                    break;
                case 9:
                    $campos = ['Condición del interruptor',
                        'Ajuste del interruptor',
                        'Potenciómetro',
                        'Ajuste de la articulación',
                        'Retorno por resorte' ];
                    break;
                case 10:
                    $campos = ['Vinculación',
                        'Ajuste (b)',
                        'Placa de fricción',
                        'Rotor',
                        'Clave del eje',
                        'Tornillos de montaje',
                        'Conexión eléctrica',
                        'Cambiar'];
                    break;
                case 11:
                    $campos = ['Funcionamiento del calentador',
                        'Condición del cableado'];
                    break;
                case 12:
                    $campos = ['BDI o medidor de horas (opcional)',
                        'Cuerno',
                        'Interruptor de inversión de seguridad',
                        'Operación de elevación - descenso',
                        'Desconexión de energía',
                        'Verifique el funcionamiento del freno',
                        'Distancia de frenado (b)',
                        'Interruptor de freno',
                        'Corte del interruptor de límite',
                        'Velocidades de desplazamiento',
                        'Capacidad nominal',
                        'Cargador'];
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
