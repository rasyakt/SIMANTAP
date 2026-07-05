<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('kondisi_sebelumnya')->nullable();
            $table->string('kondisi_baru');
            $table->string('status_sebelumnya')->nullable();
            $table->string('status_baru')->nullable();
            $table->foreignId('lokasi_sebelumnya_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('lokasi_baru_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_status_histories');
    }
};
