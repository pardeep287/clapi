<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('city_id');
            $table->string('phone_number', 20)->unique();
            $table->string('email_address', 60)->unique()->nullable();
            $table->string('password', 255);
            $table->boolean('is_verified');
            $table->boolean('is_active');
            $table->tinyInteger('role_id');
            $table->string('referral_code', 10)->unique();
            $table->unsignedBigInteger('referred_by')
                ->comment("Who referred this user? Internal relation to users.id");
            $table->smallInteger('total_referrals')
                ->comment("count of the total users referred by this user");
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('modified_by')->unsigned();
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
        Schema::dropIfExists('users');
    }
}
