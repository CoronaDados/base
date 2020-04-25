<?php

use App\Enums\ApplicationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTablePersonsAddBotOptinField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->boolean('bot_optin')->default(false)->after('more');
        });

        DB::table('persons', 'p')
            ->join('monitoring_person AS mp', 'mp.person_id', '=', 'p.id')
            ->where('mp.application', ApplicationType::WHATSAPP)
            ->update(['bot_optin' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('bot_optin');
        });
    }
}
