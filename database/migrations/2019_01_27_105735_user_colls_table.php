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
        Schema::create('usercol', function (Blueprint $table) {

            $table->increments('ID');
            $table->smallInteger('ColID');
            $table->integer('UserID')->unsigned();
            $table->boolean('Done')->nullable();
            $table->integer('Rating')->nullable();
            $table->boolean('Favorite')->nullable();
            $table->boolean('ToDo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usercol');
    }
}
