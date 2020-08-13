<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuniorTradersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junior_traders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('othername');
            $table->string('gender');
            $table->string('address');
            $table->integer('phone')->unique();
            $table->date('dob');
            $table->string('nationality');
            $table->string('state');
            $table->string('lga');
            $table->string('email')->unique();
            $table->string('image');
            $table->string('parent_name');
            $table->integer('parent_phone');
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
        Schema::dropIfExists('junior_traders');
    }
}
