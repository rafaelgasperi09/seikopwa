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
        Schema::create('formulario_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_registro_id')->unsigned()->index();
            $table->foreign('formulario_registro_id')->references('id')->on('formulario_registro')->onDelete('cascade');
            $table->integer('formulario_campo_id')->unsigned()->index();
            $table->foreign('formulario_campo_id')->references('id')->on('formulario_campos')->onDelete('cascade');
            $table->text('valor')->nullable();
            $table->enum('tipo',['text','select','textarea','date','radio','checkbox','database','file','time','number','combo','api','firma','otro','hidden','camera','files']);
            $table->text('file_path')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('formulario_data');
    }
}
