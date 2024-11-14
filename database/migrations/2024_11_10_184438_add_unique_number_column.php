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
            $table->string('number')->unique();
            $table->string('number_series');
            $table->integer('number_payment')->nullable();
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('number')->unique();
            $table->string('number_series');
            $table->integer('number_reservation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('number_series');
            $table->dropColumn('number_payment');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('number_series');
            $table->dropColumn('number_reservation');
        });
    }
};
