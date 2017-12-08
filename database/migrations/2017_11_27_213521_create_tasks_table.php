<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable()->comment('所属项目');
            $table->integer('user_id')->nullable()->comment('添加人');
            $table->dateTime('start_at')->nullable()->comment('开始日期');
            $table->dateTime('end_at')->nullable()->comment('截止日期');
            $table->integer('leader')->nullable()->comment('负责人');
            $table->text('content')->nullable()->comment('任务内容');
            $table->dateTime('received_at')->nullable()->comment('接收日期');
            $table->dateTime('builded_at')->nullable()->comment('去现场日期');
            $table->dateTime('leaved_at')->nullable()->comment('离开现场日期');
            $table->text('result')->nullable()->comment('任务完成情况');
            $table->tinyInteger('is_need_plan')->nullable()->comment('是否需要上传计划');
            $table->integer('project_phase_id')->nullable()->comment('所处阶段');
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
        Schema::dropIfExists('tasks');
    }
}
