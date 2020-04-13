<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePersonsModifyRiskGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE persons MODIFY COLUMN risk_group ENUM('Não','Gestante','Acima de 60 anos','Diabetes',
            'Problemas Cardiovasculares','Problemas Respiratórios','Imunossuprimido') DEFAULT 'Não'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
