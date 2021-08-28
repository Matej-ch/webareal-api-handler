<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WProductProperty;
use PHPUnit\Framework\TestCase;

class WProductPropertyTest extends TestCase
{

    private $url;

    private $properties;

    public function setUp(): void
    {
        $auth = file_get_contents('stubs/auth.json');
        $auth = json_decode($auth,true);
        $this->url = $auth['url'];

        $this->properties = new WProductProperty($auth['username'],$auth['password'],$auth['apiKey']);

        /** in case dev doesn't have certificate */
        $this->properties->addCurlOptions([
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
        $this->properties->searchBy($input);

        $this->assertEquals($output,$this->properties->query);
    }

    public function searchByCombinations(): array
    {
        return [
            ['?limit=10&offset=10&sortBy=id&sortDirection=asc&findBy%5Bid%5D=123',[
                'limit' => 10,
                'offset' => '10',
                'sortBy' => 'id',
                'sortDirection' => 'asc',
                'findBy' => 'id',
                'searchedString' => 123
            ]],
            ['?sortDirection=desc&findBy%5BidProperty%5D=123',[
                'sortDirection' => 'desc',
                'findBy' => 'idProperty',
                'searchedString' => '123'
            ]],
            ['',[]]
        ];
    }

    /**
     *
     * With real connection everything works correctly
     *
     * @test
     */
    public function it_returns_list_of_properties()
    {
        $this->properties->setBaseUrl($this->url);

        $this->properties->login();

        $this->assertJson($this->properties->get());
    }

    /**
     * @test
     */
    public function it_returns_property_info_as_json()
    {
        $this->properties->setBaseUrl($this->url);

        $this->properties->login();

        $result = $this->properties->view(123);

        $this->assertJson($result);

        $this->assertArrayHasKey('id',json_decode($result,true));
    }

    /**
     * @test
     */
    public function it_deletes_property_from_eshop()
    {
        $this->properties->setBaseUrl($this->url);

        $this->properties->login();

        $response = $this->properties->delete(123);

        $response = json_decode($response,true);

        $this->assertArrayHasKey('message',$response);

        $this->assertEquals('Property was removed',$response['message']);
    }

    /**
     * @test
     */
    public function it_updates_product_property()
    {
        $this->properties->setBaseUrl($this->url);

        $this->properties->login();

        $this->properties->setFields([
            'name' => 'Color',
        ]);

        $response = $this->properties->update(213);

        $this->assertEquals('Property was updated', json_decode($response, true)['message']);
    }

}
