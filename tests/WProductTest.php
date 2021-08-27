<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WProduct;
use PHPUnit\Framework\TestCase;

class WProductTest extends TestCase
{
    private $url ='https://private-anon-e78cd6001a-webareal.apiary-mock.com';

    private $userName = 'test@mail.a';

    private $password = 'test12345';

    private $apiKey = '2asdfaf16edab97f379133231w12f';

    private $products;

    public function setUp(): void
    {
        $this->products = new WProduct($this->userName,$this->password,$this->apiKey);

        /** in case dev doesn't have certificate */
        $this->products->addCurlOptions([
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
        $this->products->searchBy($input);

        $this->assertEquals($output,$this->products->query);
    }

    /**
     * https://webareal.docs.apiary.io returns malformed json in response, test always fails
     *
     * With real connection everything works correctly
     *
     * @test
     */
    public function it_returns_list_of_products_as_json()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->assertJson($this->products->get());
    }

    /**
     * https://webareal.docs.apiary.io returns malformed json in response, test always fails
     *
     * @test
     */
    public function it_returns_list_of_products_as_array()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->asArray = true;

        $this->assertIsArray($this->products->get());
    }

    /**
     * @test
     */
    public function it_returns_product_detail_info_as_json()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $result = $this->products->view(123);

        $this->assertJson($result);

        $this->assertArrayHasKey('id',json_decode($result,true));
    }

    /**
     * @test
     */
    public function it_returns_product_detail_info_as_array()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->asArray = true;

        $result = $this->products->view(123);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id',$result);

    }

    /**
     * @test
     */
    public function it_deletes_product_from_eshop()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $response = $this->products->delete(123);

        $response = json_decode($response,true);

        $this->assertArrayHasKey('message',$response);

        $this->assertEquals('Product was removed',$response['message']);
    }

    public function searchByCombinations(): array
    {
        return [
            ['?limit=10&offset=10&sortBy=id&sortDirection=asc&findBy%5Bname%5D=test+a',[
                'limit' => 10,
                'offset' => '10',
                'sortBy' => 'id',
                'sortDirection' => 'asc',
                'findBy' => 'name',
                'searchedString' => 'test a'
            ]],
            ['?sortBy=changedAt&sortDirection=desc&findBy%5BproductNumber%5D=test',[
                'sortBy' => 'changedAt',
                'sortDirection' => 'desc',
                'findBy' => 'productNumber',
                'searchedString' => 'test'
            ]],
            ['',[]]
        ];
    }
}
