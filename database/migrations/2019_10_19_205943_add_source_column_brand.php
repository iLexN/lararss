<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceColumnBrand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sources', static function (Blueprint $table) {
            $table->char('brand', 32);
            $table->index('brand');
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
            $table->dropIndex('sources_brand_index');
            $table->dropColumn('brand');
        });
    }
}
