<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeekDayToFormularioRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->string('dia_semana',20)->nullable();
            $table->integer('semana')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('nombre_archivo')->nullable();
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
            $table->dropColumn('dia_semana');
            $table->dropColumn('semana');
            $table->dropColumn('ano');
            $table->dropColumn('nombre_archivo');
        });
    }
}
