<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WCustomers;
use PHPUnit\Framework\TestCase;

class WCustomersTest extends TestCase
{
    private $url;


    private $customers;

    public function setUp(): void
    {
        $auth = file_get_contents('stubs/auth.json');
        $auth = json_decode($auth,true);
        $this->url = $auth['url'];

        $this->customers = new WCustomers($auth['username'],$auth['password'],$auth['apiKey']);

        /** in case dev doesn't have certificate */
        $this->customers->addCurlOptions([
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);
    }

    /**
     * @dataProvider searchByCombinations
     * @test
     */
    public function it_converts_searchBy_array_to_query_string($output,$input)
    {
        $this->customers->searchBy($input);

        $this->assertEquals($output,$this->customers->query);
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

    public function searchByCombinations(): array
    {
        return [
            ["?limit=10&offset=10&sortBy=id&sortDirection=desc&findBy%5Bid%5D=my+search+string",[
                'limit' => 10,
                'offset' => 10,
                'sortBy' => 'id',
                'sortDirection' => 'desc',
                'findBy' => 'id',
                'searchedString' => 'my search string'
            ]],
            ['',[]],
            ['?offset=10&sortBy=id',[
                'offset' => 10,
                'sortBy' => 'id',
            ]],
            ['',['searchedString' => 'my search string']],
            ['?findBy%5Bid%5D=my+search+string',[
                'findBy' => 'id',
                'searchedString' => 'my search string'
            ]],
        ];
    }
}
