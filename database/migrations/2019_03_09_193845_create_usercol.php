<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsercol extends Migration
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
 //           $table->boolean('Done')->default(false);
 //           $table->integer('Rating')->default(0);
//            $table->boolean('Favorite')->nullable();
 //           $table->boolean('ToDo')->nullable();
            $table->timestamp('ClimbedAt')->nullable();
            $table->timestamp('CreatedAt')->nullable();
            $table->timestamp('UpdatedAt')->nullable();

        });
		
		Schema::table('stats', function(Blueprint $table)
		{
			$table->renameColumn('StatID', 'StatTypeID');
		});
		
		Schema::table('profiles', function(Blueprint $table)
		{
			$table->index('FileName','idx_profiles_filename');
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
		
		Schema::table('stats', function(Blueprint $table)
		{
			$table->renameColumn('StatTypeID', 'StatID');
		});
		
		Schema::table('profiles', function(Blueprint $table)
		{
			$table->dropIndex('idx_profiles_filename');
		});
    }
}
