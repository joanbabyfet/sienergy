<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateApiReqLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //connection('mongodb')
        Schema::create('api_req_log', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',20)->default('')->nullable()->comment('類型，api/admin');
            $table->char('uid',32)->default('')->nullable()->comment('用戶id');
            $table->text('req_data')->nullable()->comment('请求数据，json格式');
            $table->text('res_data')->nullable()->comment('响应数据，json格式');
            $table->integer('req_time')->default(0)->nullable()->comment('请求时间');
            $table->string('req_ip',15)->default('')->nullable()->comment('请求ip');
        });
        $table = DB::getTablePrefix().'api_req_log';
        DB::statement("ALTER TABLE `{$table}` comment'api访问日志表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //connection('mongodb')
        Schema::dropIfExists('api_req_log');
    }
}
