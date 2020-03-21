<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class People extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
 
            $table->string('nm_superior'); // refactor! id_superior
            $table->string('nm_setor');
            $table->string('nm_people');
            $table->string('ds_cpf');
            $table->text('dt_nascimento');
            $tatble->int('nr_cep');
            $tatble->int('nm_uf');
            $tatble->int('nm_cidade');
            $tatble->int('nm_bairro');
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('people');
    }
}
