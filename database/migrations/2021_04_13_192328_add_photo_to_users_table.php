<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable();
            $table->integer('crm_user_id')->nullable();
            $table->integer('crm_cliente_id')->nullable();
            $table->integer('have_to_change_password')->nullable();
            $table->date('date_last_password_changed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('crm_user_id');
            $table->dropColumn('crm_cliente_id');
            $table->dropColumn('have_to_change_password');
            $table->dropColumn('date_last_password_changed');
        });
    }
}
