<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookletMembershipDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booklet_membership_deals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booklet_membership_id');
            $table->string('store_name', 100);
            $table->string('deal_name', 100);
            $table->float('actual_price', 8, 2);
            $table->float('discount_price', 8, 2);
            $table->float('payable_price', 8, 2);
            $table->text('image_path');
            $table->tinyInteger('coupons_quantity')->unsigned()->comment('No. of coupon codes given in the deal');
            $table->text('terms');
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
        Schema::dropIfExists('booklet_membership_deals');
    }
}
