<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->nullable()->comment('项目编号');
            $table->string('title')->nullable()->comment('项目名称');
            $table->integer('leader')->nullable()->comment('项目负责人');
            $table->integer('department_id')->nullable()->commnet('所属部门');
            $table->integer('agent')->nullable()->comment('现场代理负责人');
            $table->string('customers',20)->nullable('客户对接人');
            $table->string('customers_tel',20)->nullable('客户对接人电话');
            $table->tinyInteger('status')->default(0)
                ->comment('项目状态0未开始，1进行中，2已完成');
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
        Schema::dropIfExists('projects');
    }
}
