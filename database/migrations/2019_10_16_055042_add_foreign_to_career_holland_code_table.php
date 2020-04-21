<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToCareerHollandCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('career_holland_code', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('career_holland_code', function (Blueprint $table) {
            $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
            $table->foreign('holland_code_id')->references('id')->on('holland_codes')->onDelete('cascade');
        });
    }
}
