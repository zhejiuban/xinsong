<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_faults', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable()->comment('所属项目');
            $table->string('car_no')->nullable()->comment('小车编号');
            $table->dateTime('occurrenced_at')->nullable()->comment('发生时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_faults');
    }
}
