<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certificats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('habitant_id')
                ->constrained('habitants')
                ->cascadeOnDelete();
            $table->date('date_certificat');
            $table->enum('statut', ['en_attente', 'paye', 'delivre'])->default('en_attente');
            $table->unsignedInteger('montant')->default(5000);
            $table->string('reference_paiement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificats');
    }
};

