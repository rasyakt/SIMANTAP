<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('component_item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('kuantitas')->default(1);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['parent_item_id', 'component_item_id'], 'item_component_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_components');
    }
};
