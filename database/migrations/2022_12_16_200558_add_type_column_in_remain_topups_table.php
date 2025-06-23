<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnInRemainTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remain_topups', function (Blueprint $table) {
            $table->string('type', 32)->default('parcel')->comment('parcel,manual');
            $table->string('reference', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remain_topups', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('reference');
        });
    }
}
