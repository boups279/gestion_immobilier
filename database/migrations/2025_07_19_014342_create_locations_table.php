<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locataire_id')->constrained()->onDelete('cascade');
            $table->foreignId('immeuble_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('montant_mensuel', 10, 2);
            $table->decimal('caution', 10, 2)->nullable();
            $table->enum('statut', ['active', 'terminee', 'suspendue'])->default('active');
            $table->text('conditions_particulieres')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};