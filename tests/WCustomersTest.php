<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WCustomers;
use PHPUnit\Framework\TestCase;

class WCustomersTest extends TestCase
{
    private $url ='https://private-anon-e78cd6001a-webareal.apiary-mock.com';

    private $userName = 'test@mail.a';

    private $password = 'test12345';

    private $apiKey = '2asdfaf16edab97f379133231w12f';

    private $customers;

    public function setUp(): void
    {
        $this->customers = new WCustomers($this->userName,$this->password,$this->apiKey);

        /** in case dev doesn't have certificate */
        $this->customers->addCurlOptions([
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);
    }

    /**
     * @test
     */
    public function it_converts_searchBy_array_to_query_string()
    {
        $this->customers->searchBy([
            'limit' => 10,
            'offset' => 10,
            'sortBy' => 'id',
            'sortDirection' => 'desc',
            'findBy' => 'id',
            'searchedString' => 'my search string'
        ]);

        $this->assertEquals("?limit=10&offset=10&sortBy=id&sortDirection=desc&findBy%5Bid%5D=my+search+string",$this->customers->query);
    }

    /**
     * https://webareal.docs.apiary.io returns malformed json in response, test always fails
     *
     * With real connection everything works correctly
     *
     * @test
     */
    public function it_returns_list_of_customers_as_json()
    {
        $this->customers->setBaseUrl($this->url);

        $this->customers->login();

        $this->assertJson($this->customers->get());
    }

    /**
     * https://webareal.docs.apiary.io returns malformed json in response, test always fails
     * 
     * @test
     */
    public function it_returns_list_of_customers_as_array()
    {
        $this->customers->setBaseUrl($this->url);

        $this->customers->login();

        $this->customers->asArray = true;

        $this->assertIsArray($this->customers->get());
    }
}
