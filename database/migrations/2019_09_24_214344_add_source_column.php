<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sources', static function (Blueprint $table) {
            $table->dateTime('last_sync')->nullable()->default(null);
            $table->index(['status', 'last_sync']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('sources', static function (Blueprint $table) {
            $table->dropIndex('sources_status_last_sync_index');
            $table->dropColumn('last_sync');
        });
    }
}
