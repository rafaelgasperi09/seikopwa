<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsignadoToFormularioRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->string('tecnico_asignado')->nullable();
            $table->timestamp('fecha_asignacion')->nullable();
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
            $table->dropColumn('tecnico_asignado');
            $table->dropColumn('fecha_asignacion');
        });
    }
}
