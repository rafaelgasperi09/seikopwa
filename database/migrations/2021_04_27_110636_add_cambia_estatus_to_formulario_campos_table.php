<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCambiaEstatusToFormularioCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulario_campos', function (Blueprint $table) {
            $table->boolean('cambio_estatus')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulario_campos', function (Blueprint $table) {
            $table->dropColumn('cambio_estatus');
        });
    }
}
