<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->nullOnDelete();
            $table->string('tipe'); // masuk, keluar, transfer
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('stock_id');
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
