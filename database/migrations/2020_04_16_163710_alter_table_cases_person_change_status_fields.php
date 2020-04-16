<?php

use App\Enums\StatusCovidTestType;
use App\Enums\StatusCovidType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCasesPersonChangeStatusFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases_person', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status_covid', StatusCovidType::getValues())->after('user_id');
            $table->enum('status_test', StatusCovidTestType::getValues())->after('status_covid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases_person', function (Blueprint $table) {
            $table->string('status');
            $table->dropColumn('status_covid');
            $table->dropColumn('status_test');
        });
    }
}
