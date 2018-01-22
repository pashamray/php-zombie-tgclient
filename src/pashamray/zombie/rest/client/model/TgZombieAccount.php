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
    private $id;
    private $access_hash;
    private $phone;
    private $first_name;
    private $last_name;
    private $username;

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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    static function fromJson($json)
    {
        $account = new self();
        $account->setAccessHash($json->access_hash);
        $account->setId($json->id);
        $account->setFirstName($json->first_name);
        $account->setLastName($json->last_name);
        $account->setUsername($json->username);
        $account->setPhone($json->phone);
        return $account;
    }
}