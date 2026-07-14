<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_movie_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 4, 1);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'user_movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
