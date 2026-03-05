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
        Schema::create('absensi_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('matakuliah_id')->constrained('matakuliah')->onDelete('cascade');
            $table->integer('pertemuan'); // 1-9
            $table->date('tanggal');
            $table->string('materi')->nullable();
            $table->enum('status', ['Hadir', 'Tidak Hadir'])->default('Hadir');
            $table->timestamps();

            // Prevent duplicate attendance for same dosen, matakuliah, and pertemuan
            $table->unique(['dosen_id', 'matakuliah_id', 'pertemuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_dosen');
    }
};
