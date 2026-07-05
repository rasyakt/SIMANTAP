<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('merk')->nullable();
            $table->string('tipe_model')->nullable();
            $table->string('satuan')->default('unit');
            $table->text('spesifikasi')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->decimal('estimasi_harga', 15, 2)->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('has_serial_number')->default(true);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_templates');
    }
};
