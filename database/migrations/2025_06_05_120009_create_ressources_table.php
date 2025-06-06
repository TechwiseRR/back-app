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
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('creation_date')->useCurrent();
            $table->timestamp('modification_date')->useCurrent()->useCurrentOnUpdate();
            $table->enum('visibility', ['public', 'private', 'restricted'])->default('public');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('type_ressource_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('type_ressource_id')->references('id')->on('type_ressources')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};
