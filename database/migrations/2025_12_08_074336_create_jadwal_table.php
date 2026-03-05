<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('jadwal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matakuliah_id')->constrained('matakuliah')->onDelete('cascade');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->string('hari');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
