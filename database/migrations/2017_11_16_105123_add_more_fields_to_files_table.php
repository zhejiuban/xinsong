<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->text('remark')->nullable()->comment('描述');
            $table->ipAddress('ip')->nullable()->comment('上传ip');
            $table->string('upload_mode',50)->nullable()->comment('上传模式');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('remark');
            $table->dropColumn('ip');
            $table->dropColumn('upload_mode');
        });
    }
}
