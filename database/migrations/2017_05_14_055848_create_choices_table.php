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
            $table->string('type');
            $table->string('address');
            $table->string('phone');
            $table->string('price');
            $table->string('rating');
            //(longer string set Lowest common denominator max URL length: 2,083 (Internet Explorer))
            // http://stackoverflow.com/questions/219569/best-database-field-type-for-a-url
            $table->string('image_url', 2083); #restaurant image from yelp
            $table->string('more_info', 2083); # restaurant yelp url

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
