<?php

use App\FormularioRegistro;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class CreateDailyCheckDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $form = \App\Formulario::whereNombre('form_montacarga_daily_check')->first();
        //$faker = new Faker();
        if($form){
            $equipo=  \App\Equipo::get()->random(1)->first();
            $j=1;
            $mes = 1;
            $dia = 1;
            for ($i=4;$i<=12;$i++){ // 52 semanas
                if($i<10) {$mes = '0'.$i;}else{ $mes=$i;}
                $date = new \Carbon\Carbon('2020-'.$mes.'-'.$j);

                $this->command->info($date->format('F'));
                for ($j=1;$j<=$date->format('t')-1;$j++){ // dias de la semana

                    if($j<9) {$dia='0'.$j;}else{ $dia=$j;}

                    $fec = '2020-'.$mes.'-'.$dia;
                    $date2 = \Carbon\Carbon::createFromFormat('Y-m-d', $fec);
                    $dia_semana = getDayOfWeek($date2->format('w'));
                    if($date2->format('w') == 1){
                        $this->command->error('****************************************************');
                        $this->command->info('Semana :'. $date2->format('W'));
                    }
                    $this->command->info($dia_semana);

                    for($n=1;$n<=2;$n++){ // turnos

                        if($date2->format('w') <> 0){

                            $star_ok_week = $date2->startOfWeek()->format('d-m-Y');
                            $this->command->info('turno :'.$n);


                            $fr = \App\FormularioRegistro::create([
                               'creado_por'=>1,
                               'formulario_id'=>$form->id,
                               'equipo_id'=>$equipo->id,  // $equipo->id para diferentes equipos
                               'turno_chequeo_diario'=>$n,
                               'dia_semana' =>   $dia_semana,
                               'semana' => $date2->format('W'),
                               'ano'=>$date2->format('Y'),
                               'estatus'=>'C'
                            ]);

                            foreach ($form->campos()->get() as $campo){

                                $valor = null;
                                if($campo->nombre == 'semana'){
                                    $valor = $star_ok_week;
                                }elseif($campo->nombre == 'dia_semana'){
                                    $valor = $dia_semana;
                                }elseif(in_array($campo->nombre,['identificacion','seguridad','danos_estructura','fugas','ruedas','horquillas','cadenas_cables_mangueras',
                                    'bateria','conector_bateria','protectores','dispositivos_seguridad','control_handle','extintor','horometro','pito','direccion','control_traccion',
                                    'control_hidraulicos','frenos','freno','carga_bateria','indicador_descarga_bateria','desconector_poder','luces_alarma_retroceso'])){
                                    $valor = $faker->randomElement(['OK','M','R']);
                                }elseif($campo->nombre== 'lectura_horometro'){
                                    $valor = rand ( 10000 , 99999 );
                                }elseif(in_array($campo->nombre,['operador','ok_supervisor'])){
                                    $valor = '1618934906.png';
                                }else{
                                    $valor = $faker->text(50);
                                }


                                $this->command->info('Valor :'.$valor);

                                $form_data = new \App\FormularioData();
                                $form_data->formulario_registro_id = $fr->id;
                                $form_data->formulario_campo_id = $campo->id;
                                $form_data->tipo = $campo->tipo;
                                $form_data->valor = $valor;
                                $form_data->user_id = 1;
                                $form_data->save();

                            }

                            //$this->command->error('Se creo el turno '.$n.' del dia '.  getDayOfWeek($date2->format('w')).' de la semana '.$date2->format('W').' del mes de '.$date->format('F'));
                        }
                    }
                }
            }
        }
    }
}
