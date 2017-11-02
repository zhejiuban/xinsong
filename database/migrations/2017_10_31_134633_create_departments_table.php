<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('部门名称');
            $table->integer('parent_id')->default(0)->comment('父级id');
            $table->tinyInteger('level')->default(0)->comment('等级1表示，2表示分部，3表示部门');
            $table->tinyInteger('status')->default(1)->comment('状态：1可用，0不可用');
            $table->integer('sort')->default(0)->comment('排序：同级有效');
            $table->string('remark')->nullable()->comment('描述');
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
        Schema::dropIfExists('departments');
    }
}
