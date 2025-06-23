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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->length('151');
            $table->string('slug')->length('151')->nullable();        
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

          // Insert initial data into the 'cities' table
          DB::table('cities')->insert([
            [
                'title' => 'Lagos Island',
                'slug' => 'lagos-island',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Lagos Mainland',
                'slug' => 'lagos-mainland',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Port Harcourt',
                'slug' => 'port-harcourt',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more data as needed
         

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
