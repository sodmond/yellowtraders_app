<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporateTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_traders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('address');
            $table->integer('phone')->unique();
            $table->integer('other_phone');
            $table->date('dob');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('email')->unique();
            $table->string('image');
            $table->string('rep_name');
            $table->integer('rep_phone');
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
        Schema::dropIfExists('corporate_traders');
    }
}
