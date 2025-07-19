<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('immeubles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('adresse');
            $table->enum('type', ['appartement', 'maison', 'studio', 'bureau', 'commerce']);
            $table->integer('nombre_pieces');
            $table->decimal('superficie', 8, 2);
            $table->decimal('prix_mensuel', 10, 2);
            $table->text('description')->nullable();
            $table->enum('statut', ['disponible', 'loue', 'maintenance'])->default('disponible');
            $table->foreignId('proprietaire_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('immeubles');
    }
};