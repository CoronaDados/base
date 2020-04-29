<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixForiegnKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->change();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('risk_group_person', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('person_contact', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('cases_person', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->index(['user_id', 'user_type']);
        });

        Schema::table('monitoring_person', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->index(['user_id', 'user_type']);
        });

        Schema::table('personables', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->change();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->index(['personable_id', 'personable_type']);
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
            $table->dropForeign(['company_id']);
            $table->dropForeign(['person_id']);            
        });

        Schema::table('risk_group_person', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
        });

        Schema::table('person_contact', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
        });

        Schema::table('cases_person', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropIndex(['user_id', 'user_type']);
        });

        Schema::table('monitoring_person', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropIndex(['user_id', 'user_type']);
        });

        Schema::table('personables', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropIndex(['personable_id', 'personable_type']);
        });
    }
}
