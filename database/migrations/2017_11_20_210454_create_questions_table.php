<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('标题');
            $table->integer('project_id')->nullable()->comment('项目id');
            $table->integer('question_category_id')->nullable()->comment('所属板块');
            $table->integer('user_id')->nullable()->comment('上报人');
            $table->integer('receive_user_id')->nullable()->comment('受理人');
            $table->dateTime('received_at')->nullable()->comment('接收时间');
            $table->text('content')->nullable()->comment('问题详情');
            $table->text('reply_content')->nullable()->comment('回复内容');
            $table->tinyInteger('status')->nullable()->comment('问题状态：0待接收，1处理中，2已回复，3已关闭');
            $table->dateTime('finished_at')->nullable()->comment('关闭时间');
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
        Schema::dropIfExists('questions');
    }
}
