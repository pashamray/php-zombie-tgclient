<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 19.01.18
 * Time: 12:12
 */

namespace pashamray\zombie\rest\client\model;


class TgZombieChannel
{
    public $access_hash;
    public $id;
    public $title;
    public $members_count;

    static function fromJson($json)
    {
        $channel = new self();
        $channel->id = $json->id;
        $channel->access_hash =$json->access_hassh;
        $channel->members_count = $json->members_count;
        $channel->title = $json->title;
        return $channel;
    }
}