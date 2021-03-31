<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_formulario_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_registro_id')->unsigned()->index();
            $table->foreign('formulario_registro_id')->references('id')->on('app_formulario_registro')->onDelete('cascade');
            $table->integer('formulario_campo_id')->unsigned()->index();
            $table->foreign('formulario_campo_id')->references('id')->on('app_formulario_campos')->onDelete('cascade');
            $table->text('valor')->nullable();
            $table->enum('tipo',['text','select','textarea','date','radio','checkbox','database','file','time','number','combo','api','firma']);
            $table->text('file_path')->nullable();
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
        Schema::dropIfExists('app_formulario_data');
    }
}
