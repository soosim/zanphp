<?php
/**
 * Created by PhpStorm.
 * User: xiaoniu
 * Date: 16/5/19
 * Time: 下午4:00
 */
namespace Zan\Framework\Network\ServerManager;

use Zan\Framework\Foundation\Application;
use Zan\Framework\Foundation\Core\Env;
use Zan\Framework\Network\Common\HttpClient;
use Zan\Framework\Foundation\Core\Config;
use Zan\Framework\Network\Common\Curl;
use Zan\Framework\Utilities\Types\Time;

class ServerRegister
{
    public function parseConfig($config)
    {
        $extData = [];
        $ip = nova_get_ip();
        foreach ($config['services'] as $service) {
            $extData[] = [
                'language'=> 'php',
                'version' => '1.0.0',
                'timestamp'=> Time::stamp(),
                'service' => $service['service'],
                'methods' => $service['methods'],
            ];
        }
        // sys_error(json_encode($extData, JSON_PRETTY_PRINT) . "\n");
        return [
            'SrvList' => [
                [
                    'Namespace' => $config["domain"],
                    'SrvName' => $config["appName"],
                    'IP' => $ip,
                    'Port' => (int)Config::get('server.port'),
                    'Protocol' => $config["protocol"],
                    'Status' => 1,
                    'Weight' => 100,
                    'ExtData' => json_encode($extData),
                ]
            ]
        ];
    }

    public function register($config)
    {
        $haunt = Config::get('haunt');
        $httpClient = new HttpClient($haunt['register']['host'], $haunt['register']['port']);
        $body = $this->parseConfig($config);
        // sys_error("register:\n" . json_encode($body, JSON_PRETTY_PRINT) . "\n\n");
        $response = (yield $httpClient->postJson($haunt['register']['uri'], $body, null));
        $register = $response->getBody();

        sys_echo($register);
    }


}