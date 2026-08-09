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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->unique()
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->decimal('montant', 8, 2);
            $table->timestamp('date_paiement')->useCurrent();
            $table->string('mode_paiement')->default('especes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
