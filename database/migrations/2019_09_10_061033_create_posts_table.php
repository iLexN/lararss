<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('url', 255);
            $table->text('description');
            $table->dateTime('created');
            $table->text('content');
            $table->unsignedBigInteger('source_id');
            $table->timestamps();
            $table->index('source_id');
            $table->foreign('source_id')->references('id')->on('sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}
