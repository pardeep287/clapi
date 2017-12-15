<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('category_id');
            $table->string('passcode', 20)->nullable();
            $table->string('username',30)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('address1', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('address3', 255)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->float('lattitude',10,6)->nullable();
            $table->float('longitude', 10,6)->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('stores');
    }
}
