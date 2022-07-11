<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImageFirst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_firsts', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('alt')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('src')->nullable();
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
        Schema::dropIfExists('image_firsts');
    }
}
