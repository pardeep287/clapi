<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookletDealCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booklet_deal_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booklet_deal_id');
            $table->string('coupon_code', 30);
            $table->boolean('is_used');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booklet_deal_coupons');
    }
}
