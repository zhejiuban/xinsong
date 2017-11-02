<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('title')->default('')->comment('名称');
            $table->integer('parent_id')->default(0)->comment('上级菜单');
            $table->string('guard_name')->default('web')->comment('分组');
            $table->string('url')->nullable()->comment('访问url');
            $table->string('target')->default('_self')->comment('打开方式');
            $table->tinyInteger('hide')->default(0)->comment('菜单栏是否隐藏：0否1是');
            $table->string('tip')->nullable()->comment('描述');
            $table->string('uniqid')->nullable()->comment('唯一识别号');
            $table->string('icon_class')->nullable()->comment('图标样式');
            $table->tinyInteger('status')->default(1)->comment('状态：1可用0不可用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('parent_id');
            $table->dropColumn('guard_name');
            $table->dropColumn('url');
            $table->dropColumn('target');
            $table->dropColumn('hide');
            $table->dropColumn('tip');
            $table->dropColumn('uniqid');
            $table->dropColumn('icon_class');
            $table->dropColumn('status');
        });
    }
}
