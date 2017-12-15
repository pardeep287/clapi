<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookletMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booklet_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->float('price', 8, 2);
            $table->boolean('is_visible');
            $table->smallInteger('validity')->unsigned()->comment('in days');
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
        Schema::dropIfExists('booklet_memberships');
    }
}
