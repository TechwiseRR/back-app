<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('roleName')->unique();
            $table->integer('rank');
            $table->unsignedBigInteger('permissionId');
            $table->timestamps();

            // Contraintes de clé étrangère
            $table->foreign('permissionId')->references('id')->on('permissions')->onDelete('cascade');

            // Index sur permissionId pour améliorer la recherche
            $table->index('permissionId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
