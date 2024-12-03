<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('city');

            $table->string('country')->default('Indonesia');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('country');

            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->dropColumn('province')->nullable();
            $table->dropColumn('city')->nullable();
            $table->dropColumn('district')->nullable();
            $table->dropColumn('subdistrict')->nullable();
            $table->dropColumn('postal_code')->nullable();
        });
    }
};
