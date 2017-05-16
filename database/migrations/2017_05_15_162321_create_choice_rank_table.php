<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoiceRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {




        $current_time = \Carbon\Carbon::now()->toDateTimeString();

        Schema::create('choice_rank', function (Blueprint $table){

            $table->increments('id');
            $table->timestamps();
            $table->integer('choice_id')->unsigned();
            $table->integer('rank_id')->unsigned();

            $table->foreign('choice_id')->references('id')->on('choices');
            $table->foreign('rank_id')->references('id')->on('ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choice_rank');
    }
}
