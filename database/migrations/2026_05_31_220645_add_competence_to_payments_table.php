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
        Schema::table('payments', function (Blueprint $table) {
            // Guarda o mês e ano de referência (ex: 5 para maio, 2026 para 2026)
            $table->unsignedTinyInteger('reference_month')->nullable()->after('patient_id');
            $table->unsignedSmallInteger('reference_year')->nullable()->after('reference_month');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['reference_month', 'reference_year']);
        });
    }
};
