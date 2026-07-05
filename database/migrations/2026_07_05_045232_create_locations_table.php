<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi')->unique();
            $table->string('nama');
            $table->string('tipe_lokasi');
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('kapasitas')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
