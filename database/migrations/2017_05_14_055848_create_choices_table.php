<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('user_id'); # tracks the user creating the choices
            $table->string('name'); # the name of the restaurant
            $table->string('address');
            $table->string('phone');
            $table->string('price');
            $table->string('rating');
            $table->string('image_url');
            $table->string('more_info'); # restaurant yelp url

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choices');
    }
}
