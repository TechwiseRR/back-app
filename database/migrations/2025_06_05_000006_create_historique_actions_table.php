
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamp('actionDate')->useCurrent();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('ressourceId')->nullable();
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ressourceId')->references('id')->on('ressources')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_actions');
    }
};
