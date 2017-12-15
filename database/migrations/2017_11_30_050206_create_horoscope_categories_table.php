<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoroscopeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscope_categories', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 30);
            $table->string('slug', 30);
            $table->string('from_date', 10);
            $table->string('to_date', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horoscope_categories');
    }
}
