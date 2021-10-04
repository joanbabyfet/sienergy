<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMemberIncreaseDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_increase_data', function (Blueprint $table) {
            $table->string('date',10)->default('')->comment('日期');
            $table->tinyInteger("origin")->default(1)->comment('來源');
            $table->string('timezone',10)->default('')->nullable()->comment('统计时区');
            $table->integer('member_count')->default(0)->nullable()->comment("会员人数");
            $table->integer('member_increase_count')->default(0)->nullable()->comment("会员增长人数");
            $table->integer('create_time')->default(0)->nullable()->comment("创建时间");
            $table->primary(['date', 'origin']);
        });
        $table = DB::getTablePrefix().'member_increase_data';
        DB::statement("ALTER TABLE `{$table}` comment'會員增長數表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_increase_data');
    }
}
