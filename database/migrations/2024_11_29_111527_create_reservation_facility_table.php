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
        Schema::create('reservation_facility', function (Blueprint $table) {
            $table->id();
            $table->uuid('reservation_id');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->uuid('additional_facility_id');
            $table->foreign('additional_facility_id')->references('id')->on('additional_facilities')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('pcs');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_facility');
    }
};
