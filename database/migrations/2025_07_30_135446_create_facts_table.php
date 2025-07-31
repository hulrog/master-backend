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
        Schema::create('facts', function (Blueprint $table) {
            $table->bigIncrements('fact_id');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('user_id');
            $table->text('text');
            $table->date('date_entered');
            $table->string('source')->nullable();
            // $table->timestamps();

            $table->foreign('topic_id')->references('topic_id')->on('topics');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facts', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('facts');
    }
};
