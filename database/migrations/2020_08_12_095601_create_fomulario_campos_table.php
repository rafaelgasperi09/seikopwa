<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFomularioCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_campos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_id')->unsigned()->index();
            $table->foreign('formulario_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->integer('formulario_seccion_id')->unsigned()->nullable()->index();
            $table->foreign('formulario_seccion_id')->references('id')->on('formulario_secciones')->onDelete('cascade');
            $table->string('nombre',250);
            $table->string('etiqueta');
            $table->enum('tipo',['text','select','textarea','date','radio','checkbox','database','file','time','number','combo','api','firma','otros','hidden']);
            $table->text('opciones')->nullable();
            $table->string('icono')->nullable();
            $table->string('tipo_validacion')->nullable();
            $table->string('modelo')->nullable();
            $table->string('database_nombre')->nullable();
            $table->string('formato_fecha')->nullable();
            $table->boolean('show_on_list')->default(0);
            $table->boolean('requerido')->default(0);
            $table->text('descripcion')->nullable();
            $table->string('clase')->nullable();
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
        Schema::dropIfExists('formulario_campos');
    }
}
