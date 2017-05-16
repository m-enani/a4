<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $current_time = \Carbon\Carbon::now()->toDateTimeString();

        Schema::create('links', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('user_id');
            $table->integer('expires');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
    }
}
