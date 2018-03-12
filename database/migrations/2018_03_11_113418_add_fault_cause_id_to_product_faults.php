<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFaultCauseIdToProductFaults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_faults', function (Blueprint $table) {
            $table->integer('fault_cause_id')->nullable()->comment('故障现象');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_faults', function (Blueprint $table) {
            $table->dropColumn('fault_cause_id');
        });
    }
}
