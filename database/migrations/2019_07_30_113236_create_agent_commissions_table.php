<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCommissionsTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('parcel_id');
            $table->float('agent_commission', 10, 2)->default(0); // Fixed float format
            $table->tinyInteger('pay_status')->default(1)->comment('1 for paid, 0 for unpaid'); // Fixed tinyInt
            $table->string('approved_by', 255)->default('admin'); // Changed text to string
            $table->string('payment_purpose', 255)->default('agent_commission'); // Changed text to string
            $table->date('date'); // Removed default(now()), set it in model instead
            $table->text('special_note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('agent_commissions');
    }
}
