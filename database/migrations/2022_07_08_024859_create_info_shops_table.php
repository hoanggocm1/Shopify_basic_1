<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name_shop', 255);
            $table->string('domain', 255);
            $table->string('email', 255);
            $table->string('shopify_domain', 255);
            $table->string('access_token', 255);
            $table->string('plan', 255);
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
        Schema::dropIfExists('info_shops');
    }
}
