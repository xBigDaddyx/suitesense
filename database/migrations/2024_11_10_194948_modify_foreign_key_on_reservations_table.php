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
            $table->dropForeign('reservations_guest_id_foreign');
            $table->dropColumn('guest_id');
            $table->uuid('guest_id')->nullable();
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('reservations_guest_id_foreign');
            $table->dropColumn('guest_id');
            $table->uuid('guest_id')->nullable();
            $table->foreign('guest_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
