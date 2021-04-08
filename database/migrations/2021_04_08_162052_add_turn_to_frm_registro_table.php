<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTurnToFrmRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->enum('accion_carga_bateria',['entrada','salida'])->nullable();
            $table->text('turno_chequeo_diario',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->dropColumn('accion_carga_bateria');
            $table->dropColumn('turno_chequeo_diario');
        });
    }
}
