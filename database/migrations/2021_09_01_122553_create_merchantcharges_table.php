<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantchargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchantcharges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('merchantId');
            $table->integer('packageId');
            $table->integer('delivery');
            $table->integer('extradelivery');
            $table->integer('cod');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchantcharges');
    }
}
