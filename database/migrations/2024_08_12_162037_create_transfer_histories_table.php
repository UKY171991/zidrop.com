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
                
        Schema::create('transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_ids');
            $table->string('name')->length('255');
            $table->string('done_by')->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->date('date')->default(now());
            $table->text('special_note')->nullable();
            $table->string('transfer_by')->nullable();
            $table->string('batchnumber')->length('255')->unique();
            $table->string('sealnumber')->length('255')->unique();
            $table->string('tagnumber')->length('255')->unique();
            $table->unsignedBigInteger('origin_hub_id')->nullable();
            $table->unsignedBigInteger('destination_hub_id')->nullable();
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
        Schema::dropIfExists('transfer_histories');
    }
};
