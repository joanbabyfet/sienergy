<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('parent_id')->default(0)->nullable()->comment("上級id");
            $table->string('icon',50)->default('')->nullable()->comment('圖標');
            $table->string('uri',255)->default('')->nullable()->comment('URL');
            $table->tinyInteger('is_link')->default(0)->nullable()->comment('0-no;1-yes');
            $table->string('permission_name',100)->default('')->nullable()->comment('關聯權限');
            $table->string('name',50)->default('')->nullable()->comment('菜單名稱');
            $table->string('type',30)->default('')->nullable()->comment('导航類型');
            $table->string('guard_name',30)->default('')->nullable()->comment('守衛');
            $table->smallInteger("sort")->default(0)->nullable()->comment('排序');
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
        });
        $table = DB::getTablePrefix().'navigation';
        DB::statement("ALTER TABLE `{$table}` comment'菜單表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation');
    }
}
