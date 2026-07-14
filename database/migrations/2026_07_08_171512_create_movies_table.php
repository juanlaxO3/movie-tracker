<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('imdb_id')->unique();
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('year')->nullable();
            $table->date('release_date')->nullable();
            $table->string('runtime')->nullable();
            $table->text('plot')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('backdrop_url')->nullable();
            $table->string('imdb_rating')->nullable();
            $table->string('imdb_votes')->nullable();
            $table->text('genres')->nullable();
            $table->text('directors')->nullable();
            $table->text('actors')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
