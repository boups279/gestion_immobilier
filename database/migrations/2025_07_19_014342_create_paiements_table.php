<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->integer('mois_concerne');
            $table->integer('annee_concernee');
            $table->enum('type_paiement', ['especes', 'cheque', 'virement', 'carte']);
            $table->string('reference')->nullable();
            $table->enum('statut', ['paye', 'en_retard', 'annule'])->default('paye');
            $table->text('remarques')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};