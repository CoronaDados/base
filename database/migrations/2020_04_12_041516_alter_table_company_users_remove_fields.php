<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCompanyUsersRemoveFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->unsignedInteger('person_id')->after('company_id');
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('cpf');
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->string('name')->after('company_id');
            $table->string('cpf')->nullable()->after('name');
            $table->string('phone')->nullable()->after('cpf');
            $table->string('department')->nullable()->after('phone');
        });
    }
}
