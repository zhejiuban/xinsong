<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type")->nullable()->comment("文件类型");                     //文件类型
            $table->string("name")->nullable();                                         //名称
            $table->string("old_name")->nullable()->comment("文件原始名称");             //原名称
            $table->integer("width")->nullable()->comment('图片时有效');                           //宽
            $table->integer("height")->nullable()->comment('图片时有效');                          //高
            $table->string("suffix")->nullable()->comment("文件后缀名");                 //后缀名
            $table->string("file_path")->nullable()->comment("文件存储路径");            //存储路径
            $table->string("path")->nullable()->comment("文件所在路径");                 //路径
            $table->integer("size")->nullable()->comment("文件大小");        //文件大小
            $table->string('uniqid',64)->nullable()->comment('文件唯一识别号');
            $table->string('url')->nullable()->comment('访问url');
            $table->softDeletes();
            $table->timestamps();
            $table->index('name');
            $table->index('old_name');
            $table->index('uniqid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
