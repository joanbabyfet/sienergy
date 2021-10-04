<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',50)->default('')->nullable()->comment('姓名');
            $table->string('company_name',50)->default('')->nullable()->comment('公司名稱');
            $table->tinyInteger("sex")->default(1)->nullable()->comment('性別：0=女 1=男');
            $table->string('email',100)->default('')->nullable()->comment('電子郵件');
            $table->string('phone',20)->default('')->nullable()->comment('聯絡電話');
            $table->text("content")->nullable()->comment('意見');
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
        });
        $table = DB::getTablePrefix().'feedback';
        DB::statement("ALTER TABLE `{$table}` comment'問題諮詢表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
