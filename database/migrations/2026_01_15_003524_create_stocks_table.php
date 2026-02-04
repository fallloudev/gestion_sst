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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_premiere_id')->nullable();
            $table->foreignId('produit_id')->nullable();
            $table->unique(
                ['matiere_premiere_id', 'entrepot_id'],
                'stocks_mp_entrepot_unique'
            );
            $table->integer('quantite');
            $table->foreignId('entrepot_id');
            $table->date('date');
            $table->string('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
}; 
