<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemainTopupsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('remain_topups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parcel_id');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('remain_topups');
    }
}
