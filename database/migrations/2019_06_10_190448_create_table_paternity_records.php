<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaternityRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paternity_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable()->comment('所属项目');
            $table->string('car_no','50')->nullable()->comment('agv编号或其他');
            $table->text('question')->nullable()->comment('现场故障或问题描述');
            $table->text('solution')->nullable()->comment('解决办法');
            $table->tinyInteger('is_handle')->nullable()->comment('是否解决');
            $table->dateTime('closed_at')->nullable()->comment('关闭时间');
            $table->integer('user_id')->nullable()->comment('记录人');
            $table->text('remark')->nullable()->comment('备注');
            $table->softDeletes();
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
        Schema::dropIfExists('paternity_records');
    }
}
