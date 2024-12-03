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
            $table->string('state');
            $table->dropColumn('status');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('state');
            $table->dropColumn('status');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('state');
            $table->dropColumn('is_available');
            $table->dropColumn('status');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('state');
            $table->dropColumn('status');
            $table->dropColumn('has_payment');
            $table->dropColumn('is_completed_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->string('status');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->string('status');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->boolean('is_available');
            $table->string('status');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->boolean('has_payment');
            $table->boolean('is_completed_payment');
            $table->string('status');
        });
    }
};
