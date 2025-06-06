
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressource_validations', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->text('comment')->nullable();
            $table->timestamp('validation_date')->useCurrent();
            $table->unsignedBigInteger('ressource_id');
            $table->unsignedBigInteger('validator_id'); // L'utilisateur qui valide
            $table->timestamps();

            $table->foreign('ressource_id')->references('id')->on('ressources')->onDelete('cascade');
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressource_validations');
    }
};
