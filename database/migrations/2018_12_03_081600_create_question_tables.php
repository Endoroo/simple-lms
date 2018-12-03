<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_id')->unsigned();
            $table->text('question');
            $table->double('points');
            $table->string('type');
            $table->json('settings');
            $table->timestamps();
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
        });
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->json('answer');
            $table->timestamps();
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('answers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
        });
        Schema::dropIfExists('answers');

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['test_id']);
        });
        Schema::dropIfExists('questions');
    }
}
