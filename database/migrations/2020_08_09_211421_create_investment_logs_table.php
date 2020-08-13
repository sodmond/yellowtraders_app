<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('investment_id');
            $table->string('investment_type');
            $table->bigInteger('amount');
            $table->string('amount_in_words');
            $table->bigInteger('monthly_roi');
            $table->integer('monthly_%');
            $table->smallInteger('duration');
            $table->timestamps();
            $table->foreign('investment_id')->references('id')->on('investments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_logs');
    }
}
