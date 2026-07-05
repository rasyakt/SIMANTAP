<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->date('tanggal_laporan');
            $table->foreignId('dilaporkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('deskripsi_kerusakan');
            $table->string('tingkat_kerusakan')->default('Ringan');
            $table->string('tindakan')->nullable();
            $table->text('tindakan_detail')->nullable();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('vendor_eksternal')->nullable();
            $table->decimal('biaya', 15, 2)->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status_akhir')->nullable();
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('item_id');
            $table->index('tanggal_laporan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_histories');
    }
};
