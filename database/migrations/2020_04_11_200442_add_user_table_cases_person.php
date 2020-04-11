<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTableCasesPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases_person', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()
            ->after('status');
            $table->string('user_type')->nullable()->after('status');
        });
    }
    //            $table->integer('personable_id')->unsigned();
    //            $table->string('personable_type');

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases_person', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_type');
        });
    }
}
