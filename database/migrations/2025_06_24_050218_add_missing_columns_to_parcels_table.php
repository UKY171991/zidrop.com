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
        Schema::table('parcels', function (Blueprint $table) {
            // Add missing columns that exist in the MySQL data
            if (!Schema::hasColumn('parcels', 'package_value')) {
                $table->integer('package_value')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pay_return')) {
                $table->integer('pay_return')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'tax')) {
                $table->integer('tax')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'insurance')) {
                $table->integer('insurance')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'deliverymanId')) {
                $table->integer('deliverymanId')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'deliverymanAmount')) {
                $table->integer('deliverymanAmount')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'dPayinvoice')) {
                $table->integer('dPayinvoice')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'deliverymanPaystatus')) {
                $table->string('deliverymanPaystatus', 55)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pickupmanId')) {
                $table->integer('pickupmanId')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'agentAmount')) {
                $table->integer('agentAmount')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'aPayinvoice')) {
                $table->integer('aPayinvoice')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'agentPaystatus')) {
                $table->string('agentPaystatus', 55)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'productName')) {
                $table->string('productName', 191)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'productColor')) {
                $table->string('productColor', 55)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'productQty')) {
                $table->integer('productQty')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'payment_option')) {
                $table->string('payment_option', 55)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pickup_cities_id')) {
                $table->integer('pickup_cities_id')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pickup_town_id')) {
                $table->integer('pickup_town_id')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'delivery_cities_id')) {
                $table->integer('delivery_cities_id')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'delivery_town_id')) {
                $table->integer('delivery_town_id')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pickup_or_drop_option')) {
                $table->string('pickup_or_drop_option', 55)->nullable();
            }
            if (!Schema::hasColumn('parcels', 'pickup_or_drop_location')) {
                $table->text('pickup_or_drop_location')->nullable();
            }
            if (!Schema::hasColumn('parcels', 'vehicle_type')) {
                $table->string('vehicle_type', 55)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn([
                'package_value',
                'pay_return',
                'tax',
                'insurance',
                'deliverymanId',
                'deliverymanAmount',
                'dPayinvoice',
                'deliverymanPaystatus',
                'pickupmanId',
                'agentAmount',
                'aPayinvoice',
                'agentPaystatus',
                'productName',
                'productColor',
                'productQty',
                'payment_option',
                'pickup_cities_id',
                'pickup_town_id',
                'delivery_cities_id',
                'delivery_town_id',
                'pickup_or_drop_option',
                'pickup_or_drop_location',
                'vehicle_type'
            ]);
        });
    }
};
