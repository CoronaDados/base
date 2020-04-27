<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePersonsSetBirthdayDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('birthday');
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('cpf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('persons', function (Blueprint $table) {
//            $table->string('birthday')->nullable()->change();
//        });
    }
}
