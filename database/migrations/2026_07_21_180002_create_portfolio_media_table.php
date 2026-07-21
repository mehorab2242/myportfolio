<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_item_id')->constrained('portfolio_items')->cascadeOnDelete();
            $table->string('file_path');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['portfolio_item_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_media');
    }
};
