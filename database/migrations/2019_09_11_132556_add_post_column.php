<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', static function (Blueprint $table) {
            $table->boolean('status');
            $table->boolean('pick');

            $table->index('status');
            $table->index('pick');
            $table->index('created');
            $table->index(['status','created']);
            $table->index(['status','pick','created']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', static function (Blueprint $table) {
            $table->dropIndex('posts_status_index');
            $table->dropIndex('posts_pick_index');
            $table->dropIndex('posts_created_index');

            $table->dropIndex('posts_status_created_index');
            $table->dropIndex('posts_status_pick_created_index');

            $table->dropColumn('status');
            $table->dropColumn('pick');
        });
    }
}
