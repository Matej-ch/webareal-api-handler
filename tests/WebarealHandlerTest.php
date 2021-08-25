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

    /**
     * @test
     */
    public function it_logs_in_api_and_returns_json_with_token()
    {
        $handler = new WebarealHandler('test','test12345','sadad3341');

        $handler->setBaseUrl($this->url);

        /** in case dev doesn't have certificate */
        $handler->addCurlOptions([
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $handler->login();

        $this->assertEquals('<token>',$handler->getBearerToken());
    }

    /**
     * @test
     */
    public function it_attempts_to_log_in_witn_incorrect_info_and_throws_exception()
    {
        $handler = new WebarealHandler('test','test12345','sadad3341');

        $this->expectException(\Exception::class);

        $handler->login();
    }

    /**
     * @test
     */
    public function it_sends_test_request_with_token_bearer_returned_fron_login_request()
    {
        $handler = new WebarealHandler('test','test12345','sadad3341');

        $handler->setBaseUrl($this->url);

        $handler->login();

        $handler->test();
    }
}