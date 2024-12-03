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
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('notes')->nullable();
        });
        Schema::table('additional_facilities', function (Blueprint $table) {
            $table->text('unit')->default('pcs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
        Schema::table('additional_facilities', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};
