<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset')->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('item_template_id')->nullable()->constrained('item_templates')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('nomor_seri')->nullable()->unique();
            $table->date('tanggal_pengadaan')->nullable();
            $table->string('vendor')->nullable();
            $table->string('sumber')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('foto')->nullable();
            $table->string('kondisi')->default('Baik');
            $table->string('status_penggunaan')->default('Idle');
            $table->integer('jumlah')->default(1);
            $table->string('satuan')->default('unit');
            $table->text('catatan')->nullable();
            $table->string('qr_code')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['kondisi', 'status_penggunaan']);
            $table->index('lokasi_id');
            $table->index('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
