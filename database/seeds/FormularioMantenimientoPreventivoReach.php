<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoReach extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_reach',
            'nombre_menu'=>'RR - RD SERIE 52',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'RR - RD SERIE 52',
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
            'Unidad motriz',
            'Cables de alimentación y control del cableado',
            'Panel de Contactores',
            'Frenos',
            'Dirección',
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
                        'Montaje del mástil',
                        'Horquillas',
                        'Desplazador lateral',
                        'Mecanismo de extensión y de inclinación',
                        'Respaldo para carga',
                        'Respaldos del compartimiento del operador',
                        'Piso',
                        'Pedal del freno',
                        'Timón de dirección',
                        'Protector de seguridad',
                        'Retenedores de baterías',
                        'Estado de las ruedas',
                        'Ruedas de carga (derechas e izquierdas)',
                        'Ruedas de carga (derechas e izquierdas) de ruedas y agarraderas',
                        'Rueda de tracción y pernos de agarraderas',
                        'Cadena de levante',
                        'Mangueras',
                        'Cables de control',
                        'Interruptores de monitorización',
                        'Calcomanías de seguridad y placas de datos',
                        'Sujetadores ajustados y en su lugar'];
                    break;
                case 2:
                    $campos = ['Estado de la batería',
                        'Nivel de electrolito',
                        'Agua agregada',
                        'Retenedor de baterías',
                        'Estado del retenedor de baterías',
                        'Estado de los cables',
                        'Estado del conector de la batería',
                        'Separadores de la batería',
                        'Estado de los rodamientos de la batería'];
                    break;
                case 3:
                    $campos = ['Limpiar con aire a presión el polvo y la suciedad del montacargas',
                        'Limpiar con aire a baja presión todos los paneles eléctricos y los ventiladores de la unidad motriz',
                        'Verificar el funcionamiento del ventilador de la unidad motriz con un analizador A4.20',
                        'Limpiar con aire a presión el módulo de accionamiento de tracción y el ventilador de la cubierta del motor. Verificar el funcionamiento del ventilador con un analizador A4.21(sólo tracción de AC)',
                        'Lubricar todos los engrasadores',
                        'Engranajes de dirección',
                        'Montajes del rodamiento y del canal del mástil',
                        'Pedal del freno',
                        'Pivote del piso',
                        'Rodamientos de la batería (limpieza)',
                        'Lubricar las cadenas de levante',
                        'Lubricar el desplazador lateral',
                        'Lubricar el mecanismo de extensión y de inclinación',
                        'Lubricar las bisagras de la puerta',
                        'Piso flotante',
                        'Timón de control multifunciones'];
                    break;
                case 4:
                    $campos = ['Montaje fijo',
                        'Conexiones de los cables',
                        'Mangueras y conectores',
                        'Fugas',
                        'Inducidos y escobillas',
                        'Limpiar con aire a presión el polvo de la escobilla del motor',
                        'Funcionamiento de la bomba',
                        'Aplicar Armorall o un protector transparente a los cables y las mangueras del mástil'];
                    break;
                case 5:
                    $campos = ['Fugas',
                        'Montaje fijo del motor',
                        'Conexiones de los cables del motor',
                        'Inducido y escobilla (sólo tracción de DC)',
                        'Limpiar con aire a presión el polvo de la escobilla del motor',
                        'Montaje fijo al puente',
                        'Inspección y ajuste de los pernos del tope de la articulación',
                        'Funcionamiento de la bomba',
                        'Ajuste del resorte de articulación',
                        'Montaje del eje'];
                    break;
                case 6:
                    $campos = ['Estado del cable de alimentación',
                        'Conexiones del cable de alimentación',
                        'Estado del cableado',
                        'Conexiones del cableado',
                        'Conectores del tendido'];
                    break;
                case 7:
                    $campos = ['Estado del tip del contactor ED',
                        'Estado del tip del contactor P2',
                        'Conexiones del cable de control',
                        'Conexiones del cable de alimentación',
                        'Funcionamiento de los interruptores (derecho e izquierdo)'];
                    break;
                case 8:
                    $campos = ['Funcionamiento del pedal',
                        'Estado del interruptor de frenos',
                        'Ajuste de frenos accionamiento y rueda caster, cuando corresponda (a)',
                        'Rotor de las pastillas del freno'];
                    break;
                case 9:
                    $campos = ['Funcionamiento del sistema de dirección',
                        'Unidad de dirección (Orbitrol)',
                        'Motor de dirección (hidráulico)',
                        'Acoplamiento de la unidad de dirección de entrada',
                        'Pernos del montaje del motor y de la unidad de dirección'];
                    break;
                case 10:
                    $campos = ['Estado y nivel del aceite',
                        'Fugas',
                        'Tapa del respiradero',
                        'Mangueras y conectores',
                        'Filtro'];
                    break;
                case 11:
                    $campos = ['Rodamiento y rastreo: desgaste o daño',
                        'Sensor de altura (opcional)',
                        'Interruptores de altura',
                        'Estado de la cadena de levante: desgaste',
                        'Ajustes de la cadena de levante',
                        'Estado del montaje y de los cilindros',
                        'Elevación',
                        'Pasador de la horquilla del cilindro del mástil',
                        'Abrazadera de la horquilla del cilindro del portahorquillas',
                        'Extensión',
                        'Inclinación',
                        'Desplazamiento lateral',
                        'Pernos del montaje fijo a la unidad motriz',
                        'Cables de control',
                        'Topes/bumpers',
                        'Desplazador lateral: desgaste o daño',
                        'Mecanismo de extensión e inclinación: desgaste o daño'];
                    break;
                case 12:
                    $campos = ['Funcionamiento de la resistencia (circuito)',
                        'Estado del cableado'];
                    break;
                case 13:
                    $campos = ['Bocina',
                        'Visualización del panel',
                        'Desconexión eléctrica',
                        'Funcionamiento del sistema de dirección',
                        'Verificar el funcionamiento de los frenos',
                        'Distancia de parada (a)',
                        'Frenado por contramarcha (b)',
                        'Funcionamiento de extensión, inclinación y elevación',
                        'Funcionamiento del desplazamiento lateral',
                        'Funcionamiento del sistema accesorio',
                        'Funcionamiento del interruptor de altura del mástil',
                        'Funcionamiento del interruptor direccional',
                        'Control multifunciones',
                        'Capacidad indicada Luz de techo',
                        'Luces de trabajo',
                        'Ventilador (techo de protección)',
                        'Interruptores del retenedor de baterías',
                        'Funcionamiento del interruptor de la barra de entrada'];
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
            'requerido'=>1,
            'tamano'=>'col-12',
            'cambio_estatus'=>1,
            'permiso'=>'parteB'
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
            'cambio_estatus'=>0,
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
