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
            $table->dropColumn('amount');
            $table->dropColumn('method');
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2);
            $table->string('method')->nullable();
            $table->dropColumn('total_amount');
            $table->dropColumn('paid_amount');
            $table->dropColumn('payment_method');
            $table->dropColumn('paid_by');
            $table->dropColumn('paid_at');
            $table->dropColumn('refunded_at');
        });
    }
};
