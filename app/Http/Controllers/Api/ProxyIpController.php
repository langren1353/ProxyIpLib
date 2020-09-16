<?php

namespace App\Http\Controllers\Api;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProxyIpController extends Controller
{
    /**
     * 获取一个验证通过的代理IP
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return ProxyIpBusiness
     * @author jiangxianli
     * @created_at 2017-12-25 15:00:37
     */
    public function getNowValidateOneIp(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $condition = $request->all();

        $proxy_ip = $proxy_ip_business->getNowValidateOneProxyIp();

        return $this->jsonFormat($proxy_ip);
    }

    /**
     * 获取代理IP列表
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-25 15:02:42
     */
    public function getProxyIpList(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $condition = $request->only([
            'page', 'country', 'isp', 'order_by', 'order_rule'
        ]);

        $proxy_ips = $proxy_ip_business->getProxyIpList($condition);

        return $this->jsonFormat($proxy_ips);
    }

    /**
     * 网页代理IP请求测速
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return string
     * @author jiangxianli
     * @created_at 2017-12-26 15:18:46
     */
    public function proxyIpRequestWebSiteCheck(Request $request, ProxyIpBusiness $proxy_ip_business)
    {


        $protocol = $request->get('protocol');

        $ip = $request->get('ip');

        $port = $request->get('port');

        $web_link = $request->get('web_link');

        $response = $proxy_ip_business->proxyIpRequestWebSiteCheck($protocol, $ip, $port, urldecode($web_link));

        return $response;
    }


}