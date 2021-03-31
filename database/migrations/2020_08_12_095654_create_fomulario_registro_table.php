<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFomularioRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_formulario_registro', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creado_por')->unsigned()->nullable()->index();
            $table->foreign('creado_por')->references('id')->on('app_users')->onDelete('cascade');
            $table->integer('formulario_id')->unsigned()->index();
            $table->foreign('formulario_id')->references('id')->on('app_formularios')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_formulario_registro');
    }
}
