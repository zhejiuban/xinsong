<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->nullable()->comment('任务id');
            $table->text('content')->nullable()->comment('计划内容');
            $table->dateTime('started_at')->nullable()->comment('开始时间');
            $table->dateTime('finished_at')->nullable()->comment('完成时间');
            $table->dateTime('last_finished_at')->nullable()->comment('实际完成时间');
            $table->integer('user_id')->nullable()->comment('执行人');
            $table->tinyInteger('is_finished')->nullable()->comment('是否按计划完成');
            $table->text('reason')->nullable()->comment('未按计划完成原因说明');
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
        Schema::dropIfExists('plans');
    }
}
