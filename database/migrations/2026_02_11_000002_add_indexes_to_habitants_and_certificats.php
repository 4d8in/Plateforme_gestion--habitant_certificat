<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('habitants', function (Blueprint $table): void {
            $table->index('quartier');
        });

        Schema::table('certificats', function (Blueprint $table): void {
            $table->index('statut');
            $table->index('date_certificat');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('habitants', function (Blueprint $table): void {
            $table->dropIndex(['quartier']);
        });

        Schema::table('certificats', function (Blueprint $table): void {
            $table->dropIndex(['statut']);
            $table->dropIndex(['date_certificat']);
            $table->dropIndex(['created_at']);
        });
    }
};
