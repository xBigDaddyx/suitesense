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
        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->dropColumn('amount');
            $table->decimal('amount', 10, 2);
            $table->enum('invoice_type', ['reservation', 'restaurant', 'hotel', 'other'])->default('reservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_reservation_id_foreign',);
            $table->dropColumn('reservation_id');
            $table->dropColumn('amount');
            $table->json('amount');
            $table->dropColumn('invoice_type');
        });
    }
};
