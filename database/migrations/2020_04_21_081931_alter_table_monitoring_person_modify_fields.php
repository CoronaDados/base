<?php

use App\Enums\ApplicationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableMonitoringPersonModifyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoring_person', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('symptoms');
            $table->enum('application', ApplicationType::getValues())->default(ApplicationType::SISTEMA)->after('notes');
            $table->json('symptoms')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitoring_person', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('application');
            $table->json('symptoms')->change();
        });
    }
}
