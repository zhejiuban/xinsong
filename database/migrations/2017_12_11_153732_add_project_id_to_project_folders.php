<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToProjectFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_folders', function (Blueprint $table) {
            $table->integer('project_id')->nullable()->comment('所属项目');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_folders', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
