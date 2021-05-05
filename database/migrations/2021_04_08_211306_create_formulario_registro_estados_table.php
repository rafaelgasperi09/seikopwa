<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioRegistroEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_registro_estatus', function (Blueprint $table) {
            $table->id();
            $table->integer('formulario_registro_id')->unsigned()->index();
            $table->foreign('formulario_registro_id')->references('id')->on('formulario_registro')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('estatus');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->text('estatus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_registro_estatus');

        Schema::table('formulario_registro', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }
}
