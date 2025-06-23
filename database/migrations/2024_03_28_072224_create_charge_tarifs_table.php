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
        Schema::create('charge_tarifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pickup_cities_id');
            $table->unsignedBigInteger('delivery_cities_id');         
            $table->integer('deliverycharge')->length('55');
            $table->integer('extradeliverycharge')->length('55')->comment('Extra Delivery Charge per KG')->nullable();
            $table->float('codcharge', 8, 2)->comment('perchantage of COD Charge')->default(0);
            $table->float('tax', 8, 2)->comment('perchantage of tax')->default(0);
            $table->float('insurance', 8, 2)->comment('perchantage of insurance')->default(0);           
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
         // Insert initial data into the 'charge_tarifs' table
        DB::table('charge_tarifs')->insert([
            [
                'pickup_cities_id' => 1,
                'delivery_cities_id' => 1,
                'deliverycharge' => 2000,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Island to Lagos Island',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 1,
                'delivery_cities_id' => 2,
                'deliverycharge' => 2000,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Island to Lagos Mainland',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 1,
                'delivery_cities_id' => 3,
                'deliverycharge' => 3500,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Island to Port Harcourt',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 2,
                'delivery_cities_id' => 2,
                'deliverycharge' => 2000,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Mainland to Lagos Mainland',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 2,
                'delivery_cities_id' => 1,
                'deliverycharge' => 2000,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Mainland to Lagos Island',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 2,
                'delivery_cities_id' => 3,
                'deliverycharge' => 3500,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Lagos Mainland to Port Harcourt',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 3,
                'delivery_cities_id' => 3,
                'deliverycharge' => 2000,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Port Harcourt to Port Harcourt',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 3,
                'delivery_cities_id' => 2,
                'deliverycharge' => 3500,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Port Harcourt to Lagos Mainland',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pickup_cities_id' => 3,
                'delivery_cities_id' => 1,
                'deliverycharge' => 3500,
                'extradeliverycharge' => 600,
                'codcharge' => 0.5,
                'tax' => 7.5,
                'insurance' => 0.5,
                'description' => 'This is the charge for delivery from Port Harcourt to Lagos Island',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_tarifs');
    }
};
