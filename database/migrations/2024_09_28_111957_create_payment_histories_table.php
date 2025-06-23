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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type')->comment('p2p,');
            $table->string('payment_purpose')->comment('p2p,');
            $table->string('transactionId')->comment('payment_id');
            $table->string('refference_no')->comment('refference');
            $table->dateTime('paid_at');
            $table->double('amount', 10, 2); // precision 10, scale 2
            $table->double('fees', 10, 2);   // precision 10, scale 2
            $table->string('card_holder_name')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_auth_code')->nullable();
            $table->string('card_bin')->nullable();
            $table->string('card_last4')->nullable();
            $table->string('card_no')->nullable();
            $table->string('card_expirity')->nullable();
            $table->string('card_cc')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->nullable();
            $table->string('channel')->nullable();
            $table->string('currency')->nullable();
            $table->string('metadata')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('user_mac')->nullable();
            $table->string('relational_type')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->unsignedBigInteger('relational_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
