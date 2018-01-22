<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 18.01.18
 * Time: 14:38
 */

use pashamray\zombie\rest\client\TgClientException;
use PHPUnit\Framework\TestCase;

class TgClientExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(TgClientException::class);
    }
}
