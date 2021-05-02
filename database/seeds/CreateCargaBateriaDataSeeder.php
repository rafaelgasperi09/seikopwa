<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CreateCargaBateriaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $form = \App\Formulario::whereNombre('form_bat_control_carga')->first();
        //$faker = new Faker();
        if($form){

            for($i=1;$i<=300;$i++){

                $bateria =  \App\Componente::whereTipoComponenteId(2)->get()->random(1)->first();

                $fr = \App\FormularioRegistro::create([
                    'creado_por'=>1,
                    'formulario_id'=>$form->id,
                    'componente_id'=>16,
                ]);

                //$is_entrada = true;
                foreach ($form->campos()->get() as $campo){

                    $valor = null;
                    switch ($campo->nombre) {
                        case 'accion':
                            $valor = $faker->randomElement(['entrada','salida']);
                            if($valor == 'entrada'){
                                $is_entrada = false;
                            }else{
                                $is_entrada = true;
                            }
                            break;
                        case 'fecha':
                            $date = $faker->dateTimeBetween($startDate = '-160 days', $endDate = 'now', $timezone = null);
                            $valor = $date->format('Y-m-d');
                            break;
                        case 'hora_entrada':
                            $valor = $faker->time($format = 'H:i', $max = 'now');
                            break;
                        case 'horometro_salida_cuarto':
                            if(!$is_entrada) $valor = rand ( 10000 , 99999 );
                            break;
                        case 'carga_salida_cuarto':
                            if(!$is_entrada) $valor = rand ( 95 , 100 );
                            break;
                        case 'horometro_entrada_cuarto':
                            if($is_entrada) $valor = rand ( 10000 , 99999 );
                            break;
                        case 'carga_entrada_cuarto':
                            if($is_entrada) $valor = rand ( 5 , 20 );
                            break;
                        case 'horas_uso_bateria':
                            $valor = rand ( 10 , 24 );
                            break;
                        case 'h2o':
                            $valor = $faker->randomElement(['on',null]);
                            break;
                        case 'ecu':
                            $valor = $faker->randomElement(['on',null]);
                            break;
                        case 'observacion':
                            $valor = $faker->text(50);
                            break;
                    }

                    //$this->command->info('Valor :'.$valor);

                    $form_data = new \App\FormularioData();
                    $form_data->formulario_registro_id = $fr->id;
                    $form_data->formulario_campo_id = $campo->id;
                    $form_data->tipo = $campo->tipo;
                    $form_data->valor = $valor;
                    $form_data->save();

                }

            }

        }

    }
}
