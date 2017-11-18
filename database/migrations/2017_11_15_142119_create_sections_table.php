<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peoject_phases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable()->comment('项目id');
            $table->string('name',100)->nullable()->comment('项目建设阶段');
            $table->dateTime('started_at')->nullable()->comment('开始时间');
            $table->dateTime('finished_at')->nullable()->comment('完成时间');
            $table->tinyInteger('status')->nullable()->comment('状态：0未开始，1进行中，2已完成');
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
        Schema::dropIfExists('peoject_phases');
    }
}
