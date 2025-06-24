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
        Schema::table('merchantpayments', function (Blueprint $table) {
            if (!Schema::hasColumn('merchantpayments', 'parcelId')) {
                $table->integer('parcelId')->nullable();
            }
            if (!Schema::hasColumn('merchantpayments', 'done_by')) {
                $table->string('done_by', 191)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchantpayments', function (Blueprint $table) {
            $table->dropColumn(['parcelId', 'done_by']);
        });
    }
};
