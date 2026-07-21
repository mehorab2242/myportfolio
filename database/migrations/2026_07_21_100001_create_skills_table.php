<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('skill_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('level')->nullable();
            $table->enum('level_type', ['percentage', 'text', 'stars'])->default('percentage');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'category_id', 'order']);
            $table->index(['user_id', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
