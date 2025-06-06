<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->longText('content');
            $table->string('url')->nullable();
            $table->timestamp('publication_date')->useCurrent();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('validation_date')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('type_ressource_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Définir les clés étrangères avec contraintes
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('type_ressource_id')->references('id')->on('type_ressources')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};
