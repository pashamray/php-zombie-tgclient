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
                $url .= 'account/'.$params['phone'].'/user/'.$params['user_id'].'/send/message';
                break;
            case 'send_msg_group':
                $url .= 'account/'.$params['phone'].'/send/msg/group';
                break;
            case 'connect':
                $url .= 'account/'.$params['phone'].'/connect';
                break;
            case 'account_del':
            case 'account_add':
                $url .= 'account/'.$params['phone'];
                break;
            case 'account_request_code':
                 $url .= 'account/'.$params['phone'].'/code/request';
                break;
            case 'account_send_code':
                $url .= 'account/'.$params['phone'].'/code/send';
                break;
            case 'account_send_p2fa':
                $url .= 'account/'.$params['phone'].'/password/send';
                break;
            case 'get_dialogs':
            case 'get_channels':
                $url .= 'account/'.$params['phone'].'/'.$method;
                break;
            case 'get_accounts':
                $url .= 'accounts';
                break;
            case 'get_channel':
                $url .= 'account/'.$params['phone'].'/channel/'.$params['channel_id'];
                break;
            case 'get_channel_updates':
                $url .= 'account/'.$params['phone'].'/channel/'.$params['channel_id'].'/updates';
                break;
            case 'channel_get_users':
                $url .= 'account/'.$params['phone'].'/channel/'.$params['channel_id'].'/users';
                break;
            case 'chat_send_message':
                $url .= 'account/'.$params['phone'].'/chat/'.$params['chat_id'].'/send/message';
                break;
            case 'info_by_link':
                $url .= 'account/'.$params['phone'].'/channel/info_by_link';
                break;
            case 'join_by_link':
                $url .= 'account/'.$params['phone'].'/channel/join_by_link';
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
                return $responce;
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
        );
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
     * @param $channel_id
     * @return mixed
     * @throws TgClientError
     */
    public function getChannelUpdates($channel_id, $limit = 100, $from = 'now -1 day')
    {
         $url = $this->makeurl('get_channel_updates', [
             'phone' => $this->phone,
             'channel_id' => $channel_id
        ]);
        return $this->request($url, [
            'limit' => $limit,
            'offset_date' => date('Y-m-d H:i', strtotime($from))
        ]);
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
     * @param $access_hash
     * @param $text
     * @return mixed
     * @throws TgClientError
     */
    public function sendMessageToUser($user_id, $access_hash, $text)
    {
        $url = $this->makeurl('send_user_msg', [
            'phone' => $this->phone,
            'user_id' => $user_id,
        ]);
        return $this->request($url, [
            'text' => $text,
            'access_hash' => $access_hash
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
     * @param $chat_id
     * @param $text
     * @return mixed
     * @throws TgClientError
     */
    public function chatSendMessage($chat_id, $text)
    {
         $url = $this->makeurl('chat_send_message', [
             'phone' => $this->phone,
             'chat_id' => $chat_id
        ]);
        return $this->request($url, [
            'text' => $text
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
        return $this->request($url, [], 'POST');
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
        return $this->request($url);
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
    public function joinByLink($invite_link)
    {
        $url = $this->makeurl('join_by_link', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [
            'link' => $invite_link
        ]);
    }

    /**
     * @param $invite_link
     * @return mixed
     * @throws TgClientError
     */
    public function infoByLink($invite_link)
    {
        var_dump($invite_link);
        $url = $this->makeurl('info_by_link', [
            'phone' => $this->phone
        ]);
        return $this->request($url, [
            'link' => $invite_link
        ]);
    }

    /**
     * @param $channel_id
     * @param int $offset
     * @param int $limit
     * @return mixed
     * @throws TgClientError
     */
    public function getChatUsers($channel_id, $offset = 0, $limit = 100)
    {
        $url = $this->makeurl('channel_get_users', [
            'phone' => $this->phone,
            'channel_id' => $channel_id
        ]);

        return $this->request($url, [
            'offset' => $offset,
            'limit' => $limit
        ]);
    }
}