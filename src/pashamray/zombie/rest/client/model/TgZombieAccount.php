<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 19.01.18
 * Time: 10:05
 */

namespace pashamray\zombie\rest\client\model;

class TgZombieAccount
{
    public $id;
    public $access_hash;
    public $phone;
    public $first_name;
    public $last_name;
    public $username;

    static function fromJson($json)
    {
        $account = new self();
        $account->access_hash = $json->access_hash;
        $account->id = $json->id;
        $account->first_name =$json->first_name;
        $account->last_name = $json->last_name;
        $account->username =$json->username;
        $account->phone = $json->phone;
        return $account;
    }
}