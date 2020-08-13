<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trader_id')->unique();
            $table->smallInteger('trader_type');
            $table->string('full_name');
            $table->string('marital_status')->default('none');
            $table->string('gender')->default('none');
            $table->string('address');
            $table->integer('phone')->unique();
            $table->integer('other_phone')->nullable();
            $table->date('dob');
            $table->string('nationality');
            $table->string('state');
            $table->string('lga');
            $table->string('email')->unique();
            $table->string('image');
            $table->string('contact_name');
            $table->integer('contact_phone');
            $table->string('referral')->nullable();
            $table->timestamps();
            $table->foreign('trader_type')->references('id')->on('trader_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traders');
    }
}
