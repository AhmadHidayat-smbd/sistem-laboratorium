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
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->boolean('is_active')->after('tahun_ajaran')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
