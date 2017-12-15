<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnderOverUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('under_over_user_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('under_over_user_stats_id');
            $table->text('description');
            $table->tinyInteger('choice')->unsigned()->comment('0=not selected yet, 1=under, 2=lucky7, 3=over');
            $table->tinyInteger('results')->comment('0=NA, 1=lost, 2=won');
            $table->integer('bet_amount')->unsigned();
            $table->integer('won_amount')->unsigned()->comment('amount won/lost');
            $table->boolean('status')->comment('0=initialized, 1=finished');
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
        Schema::dropIfExists('under_over_user_transactions');
    }
}
