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
    private $access_hash;
    private $id;
    private $title;
    private $members_count;

    /**
     * @return mixed
     */
    public function getAccessHash()
    {
        return $this->access_hash;
    }

    /**
     * @param mixed $access_hash
     */
    public function setAccessHash($access_hash)
    {
        $this->access_hash = $access_hash;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMembersCount()
    {
        return $this->members_count;
    }

    /**
     * @param mixed $members_count
     */
    public function setMembersCount($members_count)
    {
        $this->members_count = $members_count;
    }

    static function fromJson($json)
    {
        $channel = new self();
        $channel->setId($json->id);
        $channel->setAccessHash($json->access_hassh);
        $channel->setMembersCount($json->members_count);
        $channel->setTitle($json->title);
        return $channel;
    }
}