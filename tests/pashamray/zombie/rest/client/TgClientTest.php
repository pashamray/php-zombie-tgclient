<?php

use pashamray\zombie\rest\client\model\TgZombieAccount;
use pashamray\zombie\rest\client\TgClient;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: ps
 * Date: 18.01.18
 * Time: 9:19
 */

class TgClientTest extends TestCase
{
    public function testCanBeCreateValidClient()
    {
        $this->assertInstanceOf(TgClient::class, new TgClient('localhost', 5555));
    }

    public function testAccountsReturnArrayContainsTgZombieAccount()
    {
        $fixture = $this->readFixture('accounts_ok.json');
        $this->assertJson($fixture);

        $json = json_decode($fixture);
        $accounts_json = $json->result->accounts;
    }

    public function testAccountCreateFromJson()
    {
        $fixture = $this->readFixture('account_ok.json');
        $this->assertJson($fixture);

        $json = json_decode($fixture);
        $account_json = $json->result->account;
        $account = TgZombieAccount::fromJson($account_json);

        $this->assertInstanceOf(TgZombieAccount::class,            $account);
        $this->assertAttributeNotEmpty('id',            $account);
        $this->assertAttributeNotEmpty('access_hash',   $account);
        $this->assertAttributeNotEmpty('first_name',    $account);
        $this->assertAttributeNotEmpty('last_name',     $account);
        $this->assertContains('', 'username', $account);
        $this->assertAttributeNotEmpty('phone',         $account);
    }

    public function testChannelsMethodReturnJson()
    {
        $fixture = $this->readFixture('channels_ok.json');
        $this->assertJson($fixture);
    }

    public function testChannelMethodReturnJson()
    {
        $fixture = $this->readFixture('channel_ok.json');
        $this->assertJson($fixture);
    }

    private function readFixture($fixture)
    {
        return file_get_contents(__DIR__ .'/fixtures/'. $fixture, true);
    }
}