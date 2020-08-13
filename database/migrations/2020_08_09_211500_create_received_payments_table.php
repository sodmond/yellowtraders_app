<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivedPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('investment_log_id')->unique();
            $table->string('payment_proof');
            $table->smallInteger('status');
            $table->string('admin')->nullable();
            $table->timestamps();
            $table->foreign("admin")->references('username')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_payments');
    }
}
