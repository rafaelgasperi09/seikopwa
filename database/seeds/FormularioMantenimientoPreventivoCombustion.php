<?php

use Illuminate\Database\Seeder;

class FormularioMantenimientoPreventivoCombustion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form = \App\Formulario::create([
            'nombre'=>'form_montacarga_combustion',
            'nombre_menu'=>'COMBUSTION',
            'titulo' => 'Informe de Mantenimento Preventivo',
            'creado_por'=>1,
            'tipo'=>'mant_prev'
        ]);

        $form_sec = \App\FormularioSeccion::create([
            'formulario_id'=>$form->id,
            'titulo'=>'COMBUSTION',
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
            'tamano'=>'col-12',
            'permiso'=>'parteA'
        ]);

        $seeciones = ['Inspección Visual',
            'Frenos',
            'Dirección',
            'Chasís y Neumáticos',
            'Eléctrico',
            'Mástil',
            'Accesorio',
            'Sistema hidráulico',
            'Transmisión',
            'Diferencial',
            'Motor',
            'Motor de arranque',
            'Sistema de combustible',
            'Sistema de encendido',
            'Sistema de Emisión',
            'Escape',
            'Sistema de enfriamiento',
            'Comprobación del funcionamiento y prueba de manejo'];

        $i=1;
        foreach ($seeciones as $sec){

            $form_sec = \App\FormularioSeccion::create([
                'formulario_id'=>$form->id,
                'titulo'=>$sec,
                'descripcion'=>''
            ]);

            switch ($i){
                case 1:
                    $campos = ['Pérdida de aceite',
                               'Estructura de seguridad (Techo)',
                               'Montaje del mastil',
                               'Condiciones de ruedas y neumaticos',
                               'Cinturon de seguridad/Asiento de hombro y cinturones de seguridad',
                               'Horquillas y cierres',
                               'Respaldo de la carga',
                               'Calcomanías de seguridad y placas de capacidad',
                               'Tuercas de las ruedas (flojos, faltantes)',
                               'Nota de las partes dobladas o dañadas'];
                    break;
                case 2:
                    $campos = ['Operación del freno de estacionamiento/Palanca/Pedal Pads/Cables',
                               'Operación del freno de pie/Pedales/Pedal Pads/Cables',
                               'Juego libre del pedal del freno',
                               'Nivel de Líquidos y Condiciones',
                               'Vinculación Condición/lubricante',
                               'Fugas'];
                    break;
                case 3:
                    $campos = ['Dirección Operación',
                        'Columna/caja de cambios/Montar',
                        'Dirigir elevador/Rodamiento/King Pins Lubricación',
                        'Eje de dirección/montaje/Paradas/lubricación',
                        'Dirigir Mangueras',
                        'Nivel de líquido/Fugas',
                        'Dirigir Pernos de seguridad de la rueda/juego de rodamientos/Lubricación.',
                        'Cilindro de dirección'];
                    break;
                case 4:
                    $campos = ['Chassis y Carroceria',
                        'Neumáticos/Llantas/presión',
                        'Conducir Neumáticos/Llantas/presión',
                        'Cubierta de cierre/ Bisagras/ Puntales',
                        'Tapa en Piso y Cobertor',
                        'Protección Superior',
                        'Contrapeso/Retenedores'];
                    break;
                case 5:
                    $campos = ['Interruptor de arranque en punto muerto',
                        'Medidores/Cronómetro',
                        'Switches/Relays',
                        'Los fusibles/disyuntores',
                        'Alambres/Cables/Conexiones',
                        'Motor de arranque/Solenoide/Ring Gear',
                        'Alternador/Tensión de carga',
                        'Batería/Agua/Prueba de carga de tensión',
                        'Gobernador de velocidades',
                        'Puesto de Energia Hidraulica',
                        'Cambio de aceite y filtro'];
                    break;
                case 6:
                    $campos = ['Operación y Fugas',
                        'Guía de elevacion libre Condicion/ Lubricante',
                        'Levantar Cadenas/Anclajes/lubricante',
                        'Mangueras y poleas',
                        'Railes/rodillos/Diapositivas/lubricación',
                        'Cierres/lubricante',
                        'Paradas y topes',
                        'Nivelación',
                        'Soportes/lubricante',
                        'Levante y gire desviacion',
                        'Cilindro de inclinación/Montar/Estantería/Lubricar',
                        'Levante monturas Cilindro',
                        'Transporte/lubricante',
                        'Horquillas/Cierres/lubricante',
                        'Respaldo de la carga'];
                    break;
                case 7:
                    $campos = ['Operación y Fugas',
                        'Montar Cilindro (s)',
                        'Casquillo/Diapositivas/lubricante',
                        'Marco/Montaje/Soldadura/alfombrillas ganchos inferiores'];
                    break;
                case 8:
                    $campos = ['Nivel de Aceite/Fugas',
                        'Mangueras/Conexiones',
                        'Respiradero de Tapa y filtro',
                        'Bomba/Válvulas',
                        'Palancas y Vinculación/lubricante',
                        'Unidades/u-empalmes/Cinturones'];
                    break;
                case 9:
                    $campos = ['Operación/Ruido anormal',
                        'Avance lento/Embrague Vinculación/lubricante',
                        'Palanca de cambios Varillaje de control/lubricante',
                        'Nivel de Líquidos/Estado/Fugas',
                        'Filtro (s)',
                        'Vivienda/Montaje/U-Joint y pernos',
                        'Trans. F & R de bloqueo de encendido / Deslizamiento'];
                    break;
                case 10:
                    $campos = ['Nivel de líquido/Fugas',
                        'Ruido anormal',
                        'Juego de rueda con su rodamiento/Rodamientos',
                        'Vivienda/Montaje/eje',];
                    break;
                case 11:
                    $campos = ['Ruido anormal/Humo',
                        'Nivel de Aceite y fugas/Respiradero Tapa',
                        'Gobernador de velocidades',
                        'Potencia Hidráulica',
                        'Cambio de aceite y filtro'];
                    break;
                case 12:
                    $campos = ['Filtro de aire (s)/Cubierta/Indicador de flujo',
                        'Aire de admisión Tubos Flexibles/Bloque'];
                    break;
                case 13:
                    $campos = ['Tanque/Mangueras/Conexiones/Fugas',
                        'LP Regulador/Fugas/Drenaje Propileno',
                        'LP Bloqueo de Apagado Operación',
                        'Filtro de combustible (s)',
                        'Combustible Diesel separador de agua',
                        'Pedal del acelerador/Elevador/Sensores',
                        'Bomba/Carburador/Sensores',
                        'Gobernador/Aire Válvulas/Inyectores'];
                    break;
                case 14:
                    $campos = ['Bujías/Alambres',
                        'Tapa Distribuidor/Rotor/Puntos',
                        'Bobina/Paquetes de Bobina'];
                    break;
                case 15:
                    $campos = ['ECM Condición y Códigos',
                        'PCV/Mangueras',
                        'Sensor de oxígeno',
                        'Convertidor catalítico'];
                    break;
                case 16:
                    $campos = ['Distribuidor/Silenciador/Tuberías'];
                    break;
                case 17:
                    $campos = ['Nivel de Líquidos/Condiciones/Fugas',
                        'Grados de protección fluidos F',
                        'Correas/Unidad/Condición/tensión',
                        'Radiador/Tapa/aletas/mangueras/Fugas',
                        'Condiciones del Radiador',
                        'Bomba de agua/Pérdidas',
                        'Condiciones del Abanico'];
                    break;
                case 18:
                    $campos = ['Asiento Sistema de Presencia y Cinturón de seguridad',
                        'Bocina o Pito',
                        'Alarma de Retroceso',
                        'Luces de trabajo',
                        'Luces traseras/las señales de giro/Luces de freno Luces de reversa',
                        'Luz Escolta',
                        'Operador del Abanico',
                        'Luces de trabajo del monitor de operación',
                        'Verifique Servicio y Operación del freno de estacionamiento (a)',
                        'Dirección Operación',
                        'Hacia Adelante/Reversa',
                        'Elevación e inclinación de Operación y Auxiliar',
                        'Capacidad nominal'];
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
