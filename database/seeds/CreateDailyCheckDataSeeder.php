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


            for($i=1;$i<=50;$i++){

                $equipo=  \App\Equipo::get()->random(1)->first();

                $fr = FormularioRegistro::whereEquipoId(16)   // $equipo->id para diferentes equipos
                    ->whereFormularioId($form->id)
                    ->whereRaw('created_at >= CURDATE()')
                    ->orderBy('id','DESC')
                    ->first();

                if($fr){
                    $turno = $fr->turno_chequeo_diario+1;
                }else{
                    $turno=1;
                }

                $fr = \App\FormularioRegistro::create([
                    'creado_por'=>1,
                    'formulario_id'=>$form->id,
                    'equipo_id'=>26,  // $equipo->id para diferentes equipos
                    'turno_chequeo_diario'=>$turno
                ]);

                //$is_entrada = true;
                $semana = \Carbon\Carbon::now()->subWeek(rand(1,52));
                foreach ($form->campos()->get() as $campo){

                    $valor = null;
                    if($campo->nombre == 'semana'){
                        $valor = $semana->startOfWeek()->format('d-m-Y');
                    }elseif($campo->nombre == 'dia_semana'){
                        $valor = getDayOfWeek($semana->addDays(rand(1,5))->format('N'));
                    }elseif(in_array($campo->nombre,['identificacion','seguridad','danos_estructura','fugas','ruedas','horquillas','cadenas_cables_mangueras',
                        'bateria','conector_bateria','protectores','dispositivos_seguridad','control_handle','extintor','horometro','pito','direccion','control_traccion',
                        'control_hidraulicos','frenos','freno','carga_bateria','indicador_descarga_bateria','desconector_poder','luces_alarma_retroceso'])){
                        $valor = $faker->randomElement(['OK','M','R']);
                    }elseif($campo->nombre== 'lectura_horometro'){
                        $valor = rand ( 10000 , 99999 );
                    }elseif(in_array($campo->nombre,['operador','ok_supervidor'])){
                        $valor = $faker->firstName.' '.$faker->lastName;
                    }else{
                        $valor = $faker->text(50);
                    }


                    $this->command->info('Valor :'.$valor);

                    $form_data = new \App\FormularioData();
                    $form_data->formulario_registro_id = $fr->id;
                    $form_data->formulario_campo_id = $campo->id;
                    $form_data->tipo = $campo->tipo;
                    $form_data->valor = $valor;
                    $form_data->save();

                }

                $this->command->error('****************************************************');
            }

        }
    }
}
