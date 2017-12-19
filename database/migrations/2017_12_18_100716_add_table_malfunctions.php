<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableMalfunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('malfunctions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullbale()->comment('所属项目');
            $table->string('car_no','50')->nullable()->comment('小车编号');
            $table->integer('device_id')->nullable()->comment('设备类型');
            $table->text('content')->nullable()->comment('故障现象');
            $table->integer('project_phase_id')->nullable()->comment('阶段');
            $table->integer('user_id')->nullable()->comment('故障处理人');
            $table->text('reason')->nullable()->comment('故障原因');
            $table->text('result')->nullable()->comment('故障处理');
            $table->dateTime('handled_at')->nullable()->comment('故障处理时间');
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
        Schema::dropIfExists('malfunctions');
    }
}
