<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

#This table holds all the allowable restaurant category search tersms

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('categories', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('name'); # the name of the category that will actually be displayed
            $table->string('search_term'); # the yelp api needs this specific term to search for the category

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}
