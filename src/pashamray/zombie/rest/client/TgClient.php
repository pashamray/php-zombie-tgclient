<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 10.01.18
 * Time: 10:08
 */

namespace pashamray\zombie\rest\client;

use pashamray\zombie\rest\client\model\TgZombieAccount;

class TgClient
{
    protected $API_URL = 'zombie/api';
    protected $API_VER = 'v1.0';

    private $client;
    private $host;
    private $port;

    public function __construct($host = 'localhost', $port = 5555)
    {
        $this->host = $host;
        $this->port = $port;

        $this->client = new \GuzzleHttp\Client();
    }

    private function makeurl($method, $params = [])
    {
        $url = $this->host.':'.$this->port.'/'.$this->API_URL.'/'.$this->API_VER.'/';

        switch ($method)
        {
            case 'auth':
                $url .= 'account/'.$params['phone'].'/auth';
                break;
            case 'connect':
                $url .= 'account/'.$params['phone'].'/connect';
                break;
            case 'account':
                $url .= 'account/'.$params['phone'];
                break;
            case 'dialogs':
            case 'channels':
                $url .= 'account/'.$params['phone'].'/'.$method;
                break;
            case 'accounts':
                $url .= 'accounts';
                break;
            case 'channel':
                $url .= 'account/'.$params['phone'].'/channel/'.$params['channel_id'];
                break;
        }

        return $url;
    }

    private function request($url, $params = [], $method = 'GET')
    {
        /*
        $res = $client->request('GET', $this->API_URL .'/'. $this->API_VER. '/' . $method, [
            'auth' => ['user', 'pass']
        ]);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // {"type":"User"...'
        */

        $res = $this->client->request($method, $url, $params);
        switch ($res->getStatusCode())
        {
            case 200:
                return json_decode($res->getBody());
                break;
        }
        return new TgClientException("Exception", 500);
    }

    public function accounts()
    {
        $request = $this->request(
            $this->makeurl('accounts')
        );

        $arr = [];

        foreach ($request->result->accounts as $account)
        {
            $arr[] = $this->account($account->phone);
        }

        return $arr;
    }

    public function account($phone)
    {
        $responce = $this->request(
            $this->makeurl(
                'account',
                ['phone' => $phone]
            )
        );
        $result = $responce->result;
        $json = $result->account;
        return TgZombieAccount::fromJson($json);
    }

    public function dialogs($phone)
    {
        $url = $this->makeurl('dialogs', [
            'phone' => $phone
        ]);
        return $this->request($url);
    }

    public function channels($phone)
    {
        $url = $this->makeurl('channels', [
            'phone' => $phone
        ]);
        return $this->request($url);
    }

    public function chennel($phone, $channel_id)
    {
        $url = $this->makeurl('channel', [
            'phone' => $phone,
            'channel_id' => $channel_id
        ]);
        return $this->request($url);
    }

    public function auth($phone)
    {
        $url = $this->makeurl('auth', [
            'phone' => $phone
        ]);
        return $this->request($url);
    }

    public function connect($phone)
    {
        $url = $this->makeurl('connect', [
            'phone' => $phone
        ]);
        return $this->request($url);
    }
}