<?php

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary();
            $table
                ->foreignUuid('order_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->uuid('payment_reference')
                ->unique();
            $table
                ->enum('payment_method', PaymentMethod::values());
            $table
                ->enum('payment_status', PaymentStatus::values())
                ->default(PaymentStatus::Pending->value);

            $table->decimal('amount', 10, 2);

            $table
                ->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
