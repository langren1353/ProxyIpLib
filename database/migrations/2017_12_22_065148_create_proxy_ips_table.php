<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProxyIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_ips', function (Blueprint $table) {
            $table->string('unique_id', 32);
            $table->string('ip', 15)->comment('IP地址');
            $table->string('port', 5)->comment('端口');
            $table->string('country', 20)->comment('国家');
            $table->string('ip_address', 100)->comment('IP定位地址');
            $table->tinyInteger('anonymity')->default(1)->comment('匿名度 1:透明 2:高匿');
            $table->enum('protocol', ['http', 'https'])->comment('协议');
            $table->string('isp', 20)->comment('ISP 运营商');
            $table->integer('speed')->comment('响应速度 毫秒');
            $table->timestamp('validated_at')->comment('最新校验时间');
            $table->integer('success_count')->default(1)->comment('抓取成功次数'); // 成功次数多的数据排序优先返回
            $table->integer('failed_count')->default(0)->comment('抓取失败次数');  // 失败次数过多需要对数据进行删除
            $table->double('success_ratio')->default(0.01)->comment('成功比例；10次以上才计算');  // 失败次数过多需要对数据进行删除

            $table->timestamps();

            $table->index('unique_id');
            $table->index('ip');
            $table->primary(['ip', 'port', 'protocol']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proxy_ips');
    }
}
