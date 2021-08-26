<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WebarealHandler;
use PHPUnit\Framework\TestCase;

class WebarealHandlerTest extends TestCase
{
    private $url ='https://private-anon-e78cd6001a-webareal.apiary-mock.com';

    private $userName = 'test@mail.a';

    private $password = 'test12345';

    private $apiKey = '2asdfaf16edab97f379133231w12f';

    private $handler;

    public function setUp(): void
    {
        $this->handler = new WebarealHandler($this->userName,$this->password,$this->apiKey);

        /** in case dev doesn't have certificate */
        $this->handler->addCurlOptions([
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);
    }

    /**
     * @test
     */
    public function it_removes_last_slash_from_url()
    {
        $this->handler->setBaseUrl('https://api.premium-wask.cz/');

        $this->assertEquals('https://api.premium-wask.cz',$this->handler->getBaseUrl());
    }

    /**
     * @test
     */
    public function it_logs_in_api_and_returns_json_with_token()
    {
        $this->handler->setBaseUrl($this->url);

        $this->handler->login();

        $this->assertNotEmpty($this->handler->getBearerToken());
    }

    /**
     * @test
     */
    public function it_attempts_to_log_in_witn_incorrect_info_and_throws_exception()
    {
        $this->expectException(\Exception::class);

        $this->handler->login();
    }

    /**
     * @test
     */
    public function it_sends_test_request_with_token_bearer_returned_from_login_request()
    {
        $this->handler->setBaseUrl($this->url);

        $this->handler->login();

        $response = $this->handler->test();

        $this->assertEquals("Access was granted",json_decode($response,true)['message']);
    }

    /**
     * @test
     */
    public function it_returns_api_info()
    {
        $this->handler->setBaseUrl($this->url);

        $response = $this->handler->apiInfo();

        $this->assertJson($response);

        $this->assertArrayHasKey('request',json_decode($response,true));
    }
}