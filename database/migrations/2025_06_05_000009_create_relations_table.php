<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['friend', 'follow', 'block']);
            $table->timestamp('creation_date')->useCurrent();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('related_user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('related_user_id')->references('id')->on('users')->onDelete('cascade');

            // Empêcher les relations dupliquées
            $table->unique(['user_id', 'related_user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};
