<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->char('id',36); //TODO check GUID V4 -> generate in model (pacakge: uuid-ramsey php)
            $table->primary('id');
            $table->bigInteger('language_id')->nullable()->unsigned();
            $table->integer('age')->nullable()->unsigned();
            $table->integer('rating')->nullable()->unsigned();
            $table->dateTime('arrival_time'); //not nullable, because this gets filled in when user is added to db
            $table->dateTime('departure_time')->nullable();

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitors', function (Blueprint $table){
            $table->dropForeign(['language_id']);
        });
        Schema::dropIfExists('visitors');
    }
}
