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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('external_id');
            $table->float('amount');
            $table->string('payer_email')->nullable();
            $table->string('description')->nullable();
            $table->string('invoice_duration')->nullable();
            $table->string('callback_virtual_account_id')->nullable();
            $table->boolean('shoudl_send_email')->default(false);
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->uuid('customer_notification_preference_id');
            $table->foreign('customer_notification_preference_id')->references('id')->on('notification_preferences')->onDelete('cascade');
            $table->string('success_redirect_url')->nullable();
            $table->string('failure_redirect_url')->nullable();
            $table->json('payment_methods')->nullable();
            $table->string('mid_label')->nullable();
            $table->boolean('should_authenticate_credit_card')->default(false);
            $table->string('currency')->nullable();
            $table->float('reminder_time')->default(0);
            $table->string('local')->nullable();
            $table->string('reminder_time_unit')->nullable();
            $table->json('items')->nullable();
            $table->json('fees')->nullable();
            $table->string('number')->unique();
            $table->string('number_series');
            $table->integer('number_order')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
