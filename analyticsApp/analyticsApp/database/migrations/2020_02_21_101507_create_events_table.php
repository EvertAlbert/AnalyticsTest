<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('visitor_id',36);
            $table->bigInteger('action_id')->unsigned();
            $table->bigInteger('page_id')->unsigned();
            $table->string('message')->nullable();
            $table->bigInteger('product_view_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('product_view_id')->references('id')->on('product_views')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table){
            $table->dropForeign(['visitor_id']);
            $table->dropForeign(['action_id']);
            $table->dropForeign(['page_id']);
            $table->dropForeign(['product_view_id']);
        });
        Schema::dropIfExists('events');
    }
}
