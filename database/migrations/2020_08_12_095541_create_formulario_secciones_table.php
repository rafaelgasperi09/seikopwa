<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioSeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_formulario_secciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_id')->unsigned()->index();
            $table->foreign('formulario_id')->references('id')->on('app_formularios')->onDelete('cascade');
            $table->text('titulo');
            $table->text('clase')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tamano')->nullable();
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
        Schema::dropIfExists('app_formulario_secciones');
    }
}
