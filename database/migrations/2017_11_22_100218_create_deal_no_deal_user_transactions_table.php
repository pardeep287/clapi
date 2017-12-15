<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealNoDealUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_no_deal_user_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deal_no_deal_user_stats_id');
            $table->text('description');
            $table->unsignedInteger('entrance_fee');
            $table->unsignedInteger('amount')
                ->comment('amount won');
            $table->boolean('status')
                ->comment('0=initialized, 1=finished');
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
        Schema::dropIfExists('deal_no_deal_user_transactions');
    }
}

