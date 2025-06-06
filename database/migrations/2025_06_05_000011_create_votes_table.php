<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['like', 'dislike']);
            $table->timestamp('creation_date')->useCurrent();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ressource_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ressource_id')->references('id')->on('ressources')->onDelete('cascade');

            // Empêcher qu'un utilisateur vote plusieurs fois sur la même ressource
            $table->unique(['user_id', 'ressource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
