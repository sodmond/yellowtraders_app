<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYellowTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yellow_traders', function (Blueprint $table) {
            //$table->bigIncrements('id');
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('othername')->nullable();
            $table->string('marital_status');
            $table->string('gender');
            $table->string('address');
            $table->integer('phone')->unique();
            $table->integer('other_phone');
            $table->date('dob');
            $table->string('nationality');
            $table->string('state');
            $table->string('lga');
            $table->string('email')->unique();
            $table->string('image');
            $table->string('nok_name');
            $table->integer('nok_phone');
            $table->string('referral')->nullable();
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
        Schema::dropIfExists('yellow_traders');
    }
}
