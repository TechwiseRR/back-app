<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('info_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('firstName');
            $table->string('lastName');
            $table->text('address')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('updateDate')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            // Contraintes de clé étrangère
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');

            // Index pour améliorer la recherche et la performance
            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_profiles');
    }
};
