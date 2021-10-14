<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->char('id',32)->default('')->comment('用戶id');
            $table->string('realname',50)->default('')->nullable()->comment('姓名');
            $table->string('email', 100)->default('')->nullable()->comment('信箱');
            $table->string('username', 20)->default('')->nullable()->comment("用户帐号");
            $table->string('password', 60)->default('')->nullable()->comment("用户密码");
            $table->tinyInteger("status")->default(1)->nullable()->comment('帐号状态 1:正常 0:禁止登陆');
            $table->string('safe_ips', 200)->default('')->nullable()->comment('登陆IP限制');
            $table->tinyInteger('is_first_login')->default(1)->nullable()->comment('是否首次登录');
            $table->tinyInteger('is_audit')->default(0)->nullable()->comment('登陆是否需要后台进行人工审核 0: 不需要 1:需要');
            $table->integer('session_expire')->default(1440)->nullable()->comment('SESSION有效期，默认24分钟');
            $table->string('session_id', 50)->default('')->nullable()->comment('登陆时session_id');
            $table->string('reg_ip', 15)->default('')->nullable()->comment('注册ip');
            $table->integer('login_time')->default(0)->nullable()->comment('最后登录时间');
            $table->string('login_ip', 15)->default('')->nullable()->comment('最后登录IP');
            $table->string('remember_token', 100)->default('')->nullable()->comment('');
            $table->string('api_token', 60)->default('')->nullable()->comment("");
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
            $table->primary(['id']);
        });
        $table = DB::getTablePrefix().'admin_users';
        DB::statement("ALTER TABLE `{$table}` comment'用戶表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
