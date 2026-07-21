<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('title');
            $table->text('bio')->nullable();
            $table->longText('about')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('location')->nullable();
            $table->boolean('email_public')->default(false);
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
