<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 10.01.18
 * Time: 10:08
 */

namespace pashamray\zombie\rest\client;

use pashamray\zombie\rest\client\errors\TgClientError;
use yii\base\Component;

class TgClient extends Component
{
    protected $API_URL = 'zombie/api';
    protected $API_VER = 'v1.0';

    public $host;
    public $port;

    private $phone;
    private $client;

    public function init()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function account($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    private function makeurl($method, $params = [])
    {
        $url = $this->host.':'.$this->port.'/'.$this->API_URL.'/'.$this->API_VER.'/';

        switch ($method)
        {
            case 'send_user_msg':
                $url .= 'accounts/'.$params['phone'].'/send/msg/user';
                break;
            case 'send_msg_group':
                $url .= 'accounts/'.$params['phone'].'/send/msg/group';
                break;
            case 'connect':
                $url .= 'accounts/'.$params['phone'].'/connect';
                break;
            case 'account_del':
            case 'account_add':
                $url .= 'accounts/'.$params['phone'];
                break;
            case 'account_request_code':
                 $url .= 'accounts/'.$params['phone'].'/code/request';
                break;
            case 'account_send_code':
                $url .= 'accounts/'.$params['phone'].'/code/send';
                break;
            case 'account_send_p2fa':
                $url .= 'accounts/'.$params['phone'].'/send/2fa_pass';
                break;
            case 'get_dialogs':
            case 'get_channels':
                $url .= 'accounts/'.$params['phone'].'/'.$method;
                break;
            case 'get_accounts':
                $url .= 'accounts';
                break;
            case 'get_channel':
                $url .= 'accounts/'.$params['phone'].'/channel/'.$params['channel_id'];
                break;
            case 'channel_get_users':
                $url .= 'accounts/'.$params['phone'].'/channel/'.$params['channel_id'].'/users';
                break;
                break;
            case 'invite_info':
                $url .= 'accounts/'.$params['phone'].'/channel/invite/info';
                break;
            case 'join_by_invite':
                $url .= 'accounts/'.$params['phone'].'/channel/invite/join';
                break;
        }

        return $url;
    }

    /**
     * @param $url
     * @param array $params
     * @param string $method
     * @return mixed
     * @throws TgClientError
     */
    private function request($url, $params = [], $method = 'GET')
    {
        $options = [];

        switch ($method)
        {
            case 'POST':
                $options = [
                    'form_params' => $params
                ];
                break;
            case 'GET':
                $options = [
                    'query' => $params
                ];
                break;

        }

        $res = $this->client->request($method, $url, $options);
        switch ($res->getStatusCode())
        {
            case 200:
                $responce = json_decode($res->getBody());
                if (isset($responce->error))
                {
                    throw new TgClientError($responce->error->message, $responce->error->code);
                }
                return $responce->result;
                break;
        }
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function getAccounts()
    {
        return $this->request(
            $this->makeurl('get_accounts')
        )->accounts;
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function getDialogs()
    {
        $url = $this->makeurl('get_dialogs', [
            'phone' => $this->phone
        ]);
        return $this->request($url);
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function getChannels()
    {
        $url = $this->makeurl('get_channels', [
            'phone' => $this->phone
        ]);
        return $this->request($url);
    }

    /**
     * @param $channel_id
     * @return mixed
     * @throws TgClientError
     */
    public function getChennel($channel_id)
    {
        $url = $this->makeurl('get_channel', [
            'phone' => $this->phone,
            'channel_id' => $channel_id
        ]);
        return $this->request($url);
    }

    /**
     * @param $link
     * @return mixed
     * @throws TgClientError
     */
    public function channelGetInviteInfo($link)
    {
        $url = $this->makeurl('invite_info', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [
            'link' => $link
        ]);
    }

    /**
     * @param $user_id
     * @param $text
     * @return mixed
     * @throws TgClientError
     */
    public function userSendMessage($user_id, $text)
    {
        $url = $this->makeurl('send_user_msg', [
            'phone' => $this->phone,
        ]);
        return $this->request($url, [
            'user_id' => $user_id,
            'text' => $text
        ], 'POST');
    }

    /**
     * @param $group_id
     * @param $msg
     * @return mixed
     * @throws TgClientError
     */
    public function channelSendMessage($group_id, $msg)
    {
        $url = $this->makeurl('send_channel_msg', [
            'phone' => $this->phone,
        ]);
        return $this->request($url, [
            'group_id' => $group_id,
            'text' => $msg
        ], 'POST');
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function accountAdd()
    {
        $url = $this->makeurl('account_add', [
            'phone' => $this->phone
        ]);
        return $this->request($url);
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function accountDelete()
    {
        $url = $this->makeurl('account_del', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [], 'DELETE');
    }

    /**
     * @return mixed
     * @throws TgClientError
     */
    public function accountRequestCode()
    {
        $url = $this->makeurl('account_request_code', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [], 'POST');
    }

    /**
     * @param $code
     * @return mixed
     * @throws TgClientError
     */
    public function accountSendCode($code)
    {
        $url = $this->makeurl('account_send_code', [
            'phone' => $this->phone,
        ]);
        return $this->request($url, [
            'code' => $code,
        ], 'POST');
    }

    /**
     * @param $password
     * @return mixed
     * @throws TgClientError
     */
    public function accountSendPassword($password)
    {
        $url = $this->makeurl('account_send_p2fa', [
            'phone' => $this->phone,
        ]);

        return $this->request($url, [
            'password' => $password
        ], 'POST');
    }

    /**
     * @throws TgClientError
     */
    public function connect()
    {
        $url = $this->makeurl('connect', [
            'phone' => $this->phone
        ]);

        return $this->request($url);
    }

    /**
     * @param $invite_link
     * @return mixed
     * @throws TgClientError
     */
    public function joinByInviteLink($invite_link)
    {
        $url = $this->makeurl('join_by_invite', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [
            'link' => $invite_link
        ], 'POST');
    }

    /**
     * @param $channel_id
     * @param int $offset
     * @param int $limit
     * @return mixed
     * @throws TgClientError
     */
    public function channelGetUsers($channel_id, $offset = 0, $limit = 100)
    {
        $url = $this->makeurl('channel_get_users', [
            'phone' => $this->phone,
            'channel_id' => $channel_id
        ]);

        return $this->request($url, [
            'offset' => $offset,
            'limit' => $limit
        ])->users;
    }
}