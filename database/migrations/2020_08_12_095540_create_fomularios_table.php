<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFomulariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('nombre_menu',20);
            $table->text('titulo')->nullable();
            $table->text('subtitulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('creado_por')->unsigned()->nullable()->index();
            $table->foreign('creado_por')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('formularios');
    }
}
