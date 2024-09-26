<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('tags')->nullable();
            $table->time('time')->nullable();
            $table->string('attendance')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_listings');
    }
}
