<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kategori_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('item_template_id')->nullable()->constrained('item_templates')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->integer('jumlah_stok')->default(0);
            $table->integer('ambang_batas_minimum')->default(5);
            $table->string('satuan')->default('unit');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('lokasi_id');
            $table->index('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
