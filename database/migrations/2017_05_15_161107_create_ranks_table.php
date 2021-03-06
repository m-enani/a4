<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {



        $current_time = \Carbon\Carbon::now()->toDateTimeString();

        Schema::create('ranks', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('description');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ranks');
    }
}
