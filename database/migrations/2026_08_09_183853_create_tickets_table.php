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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('statut', [
                'recu',
                'en_traitement',
                'pret',
                'recupere',
                'annule',
            ])->default('recu');
            $table->timestamp('date_depot')->useCurrent();
            $table->timestamp('date_recuperation')->nullable();
            $table->decimal('montant_total', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
