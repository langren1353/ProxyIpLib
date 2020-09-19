<?php

namespace App\Jobs;

use App\Http\Business\ProxyIpBusiness;
use Carbon\Carbon;

class ClearProxyIpJob extends Job
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = "clear-ip";

    /**
     * 透明度
     *
     * @var
     */
    private $proxy_ip;

    /**
     * @var
     */
    private $expired_at;

    /**
     * ProxyIpLocationJob constructor.
     * @param array $proxy_ip
     */
    public function __construct(array $proxy_ip)
    {
        $this->proxy_ip = $proxy_ip;
        $this->expired_at = time() + 120;
    }

    /**
     * @param ProxyIpBusiness $proxy_ip_business
     * @throws \App\Exceptions\JsonException
     * @author jiangxianli
     * @created_at 2019-10-23 16:47
     */
    public function handle(ProxyIpBusiness $proxy_ip_business)
    {

        //超时
        if ($this->expired_at <= time()) {
            return;
        }

        //检查是否存在
        $proxy_ip = $proxy_ip_business->getProxyIpList([
            'unique_id' => $this->proxy_ip['unique_id'],
            'first'     => 'true'
        ]);

        if (!$proxy_ip) {
            return;
        }
        $success_count = $proxy_ip['success_count'];
        $failed_count = $proxy_ip['failed_count'];
        $total_count = $success_count + $failed_count;
        $success_ratio = 0;
        if ($total_count > 10) {
            // 一天24小时 * 6
            $success_ratio = $success_count / $total_count;
            // 失败次数较多，尝试删掉他
            if($success_ratio < 0.4){
                $proxy_ip_business->deleteProxyIp($proxy_ip['unique_id']);
            }
        }
        try {
            //测速及可用性检查
            $speed = $proxy_ip_business->ipSpeedCheck($proxy_ip['ip'], $proxy_ip['port'], $proxy_ip['protocol']);
            //更新测速信息
            $proxy_ip_business->updateProxyIp($proxy_ip['unique_id'], [
                'speed'        => $speed,
                'validated_at' => Carbon::now(),
                'success_count'=> $proxy_ip['success_count'] + 1,
                'success_ratio'=> $success_ratio,
            ]);
        } catch (\Exception $exception) {
            $proxy_ip_business->updateProxyIp($proxy_ip['unique_id'], [
                'validated_at' => Carbon::now(),
                'failed_count' => $proxy_ip['failed_count'] + 1,
                'success_ratio'=> $success_ratio,
            ]);
        }

        usleep(0.2 * 1000 * 1000);

        $this->delete();
    }
}
