<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('portfolio_categories')
                ->nullOnDelete();
            $table->string('client_name')->nullable();
            $table->string('project_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'order']);
            $table->index(['user_id', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
