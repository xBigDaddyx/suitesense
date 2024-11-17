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
            $table->unsignedBigInteger('checked_in_by')->nullable();
            $table->foreign('checked_in_by')->references('id')->on('users');
            $table->unsignedBigInteger('checked_out_by')->nullable();
            $table->foreign('checked_out_by')->references('id')->on('users');
            $table->enum('guest_status', ['pending', 'checked-in', 'checked-out'])->default('pending');
            $table->timestamp('guest_check_in_at')->nullable();
            $table->timestamp('guest_check_out_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('checked_in_by');
            $table->dropColumn('checked_out_by');
            $table->dropColumn('guest_status');
            $table->dropColumn('guest_check_in_at');
            $table->dropColumn('guest_check_out_at');
        });
    }
};
