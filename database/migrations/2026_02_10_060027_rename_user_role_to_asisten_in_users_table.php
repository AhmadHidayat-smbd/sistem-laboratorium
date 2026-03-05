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
        Schema::table('users', function (Blueprint $table) {
            // Change enum values. We use string first to be safe if driver doesn't support enum changes easily
            $table->string('role')->default('asisten')->change();
        });
        
        // Update existing data
        DB::table('users')->where('role', 'user')->update(['role' => 'asisten']);
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'asisten'])->default('asisten')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });

        DB::table('users')->where('role', 'asisten')->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};
