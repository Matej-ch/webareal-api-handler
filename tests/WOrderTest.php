<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WOrder;
use PHPUnit\Framework\TestCase;

class WOrderTest extends TestCase
{
    private $url;

    private $orders;

    public function setUp(): void
    {
        $auth = file_get_contents('stubs/auth.json');
        $auth = json_decode($auth,true);
        $this->url = $auth['url'];

        $this->orders = new WOrder($auth['username'],$auth['password'],$auth['apiKey']);

        /** in case dev doesn't have certificate */
        $this->orders->addCurlOptions([
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
        $this->orders->searchBy($input);

        $this->assertEquals($output,$this->orders->query);
    }

    /**
     * https://webareal.docs.apiary.io returns malformed json in response, test always fails
     *
     * Live server endpoint returns response correctly
     *
     * @test
     */
    public function it_returns_list_of_orders()
    {
        $this->orders->setBaseUrl($this->url);

        $this->orders->login();

        $this->assertJson($this->orders->get());
    }

    /**
     * @test
     */
    public function it_returns_list_of_states()
    {
        $this->orders->setBaseUrl($this->url);

        $this->orders->login();

        $this->assertJson($this->orders->states());
    }

    /**
     * Test endpoint returns malformed json
     * @test
     */
    public function it_returns_order_detail_info_as_json()
    {
        $this->orders->setBaseUrl($this->url);

        $this->orders->login();

        $result = $this->orders->view(123);

        $this->assertJson($result);

        $this->assertArrayHasKey('id',json_decode($result,true));
    }

    /**
     * @test
     */
    public function it_deletes_order_from_eshop()
    {
        $this->orders->setBaseUrl($this->url);

        $this->orders->login();

        $response = $this->orders->delete(123);

        $response = json_decode($response,true);

        $this->assertArrayHasKey('message',$response);

        $this->assertEquals('Order was removed',$response['message']);
    }

    /**
     * @test
     */
    public function it_updates_order()
    {
        $this->orders->setBaseUrl($this->url);

        $this->orders->login();

        $this->orders->setFields([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'city' => 'Brno',
            "isPaid" => false,
            "state" => "Vyřízeno", // order state must be changed through name, which is stupid
        ]);

        $response = $this->orders->update(213);

        $this->assertEquals('Order was updated', json_decode($response, true)['message']);
    }

    public function searchByCombinations(): array
    {
        return [
            ['?limit=10&offset=10&sortBy=created&sortDirection=asc&findBy%5Bname%5D=test+a',[
                'limit' => 10,
                'offset' => '10',
                'sortBy' => 'created',
                'sortDirection' => 'asc',
                'findBy' => 'name',
                'searchedString' => 'test a'
            ]],
            ['?sortBy=changedAt&sortDirection=desc&findBy%5BorderNumber%5D=test',[
                'sortBy' => 'changedAt',
                'sortDirection' => 'desc',
                'findBy' => 'orderNumber',
                'searchedString' => 'test'
            ]],
            ['',[]]
        ];
    }
}
