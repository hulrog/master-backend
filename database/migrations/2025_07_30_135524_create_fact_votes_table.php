<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fact_votes', function (Blueprint $table) {
            $table->bigIncrements('fact_vote_id');
            $table->unsignedBigInteger('fact_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('rating'); 
            // $table->timestamps();

            $table->foreign('fact_id')->references('fact_id')->on('facts');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fact_votes', function (Blueprint $table) {
            $table->dropForeign(['fact_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('fact_votes');
    }
};
