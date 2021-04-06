<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFormulrioRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->integer('equipo_id')->unsigned()->index()->nullable();
            $table->integer('cliente_id')->unsigned()->index()->nullable();
            $table->integer('componente_id')->unsigned()->index()->nullable();
            $table->integer('solicitud_id')->unsigned()->index()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reembolso_cargo', function (Blueprint $table) {
            $table->dropColumn('equipo_id');
            $table->dropColumn('cliente_id');
            $table->dropColumn('componente_id');
            $table->dropColumn('solicitud_id');
        });
    }
}
