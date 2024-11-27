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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_customer_id_foreign');
            $table->dropColumn('customer_id');

            //user
            $table->unsignedBigInteger('customer_id')->after('plan_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            //hotel
            $table->uuid('hotel_id')->after('plan_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign('licenses_customer_id_foreign');
            $table->dropColumn('customer_id');

            //user
            $table->unsignedBigInteger('customer_id')->after('plan_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('hotel_id')->after('plan_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
