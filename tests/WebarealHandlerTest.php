<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WebarealHandler;
use PHPUnit\Framework\TestCase;

class WebarealHandlerTest extends TestCase
{
    private $url ='https://private-anon-e78cd6001a-webareal.apiary-mock.com';
    
    /**
     * @test
     */
    public function it_removes_last_slash_from_url()
    {
        $handler = new WebarealHandler('test','test12345','sadad3341');

        $handler->setBaseUrl('https://api.premium-wask.cz/');

        $this->assertEquals('https://api.premium-wask.cz',$handler->getBaseUrl());
    }

    public function it_logs_in_api_and_returns_json_with_token()
    {
        
    }

    public function it_attempts_to_log_in_witn_incorrect_info_and_returns_error()
    {

    }
}