<?php

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
}