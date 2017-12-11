<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectFolderIdToFileProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_project', function (Blueprint $table) {
            $table->integer('project_folder_id')->nullable()->comment('所属项目文档分类');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_project', function (Blueprint $table) {
            $table->dropColumn('project_folder_id');
        });
    }
}
