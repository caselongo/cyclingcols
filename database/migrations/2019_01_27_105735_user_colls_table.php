<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserCollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userCol', function (Blueprint $table) {

            $table->increments('id');
            $table->smallInteger('ColID');
            $table->integer('user_id')->unsigned();
            $table->boolean('done')->nullable();
            $table->integer('rating')->nullable();
            $table->boolean('favorite')->nullable();
            $table->boolean('todo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userCol');
    }
}
