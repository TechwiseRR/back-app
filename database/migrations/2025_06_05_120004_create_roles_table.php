<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('permissionId')->nullable();
            $table->timestamps();

            $table->foreign('permissionId')->references('id')->on('permissions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
