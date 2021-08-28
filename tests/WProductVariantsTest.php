<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WProductVariants;
use PHPUnit\Framework\TestCase;

class WProductVariantsTest extends TestCase
{
    private $url;

    private $variants;

    public function setUp(): void
    {
        $auth = file_get_contents('stubs/auth.json');
        $auth = json_decode($auth,true);
        $this->url = $auth['url'];

        $this->variants = new WProductVariants($auth['username'],$auth['password'],$auth['apiKey']);

        /** in case dev doesn't have certificate */
        $this->variants->addCurlOptions([
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
        $this->variants->searchBy($input);

        $this->assertEquals($output,$this->variants->query);
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
            ['?sortDirection=desc&findBy%5BidProduct%5D=123',[
                'sortDirection' => 'desc',
                'findBy' => 'idProduct',
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
    public function it_returns_list_of_variants()
    {
        $this->variants->setBaseUrl($this->url);

        $this->variants->login();

        $this->assertJson($this->variants->get());
    }

    /**
     * @test
     */
    public function it_returns_variant_info_as_json()
    {
        $this->variants->setBaseUrl($this->url);

        $this->variants->login();

        $result = $this->variants->view(123);

        $this->assertJson($result);

        $this->assertArrayHasKey('id',json_decode($result,true));
    }

    /**
     * @test
     */
    public function it_deletes_variant_from_eshop()
    {
        $this->variants->setBaseUrl($this->url);

        $this->variants->login();

        $response = $this->variants->delete(123);

        $response = json_decode($response,true);

        $this->assertArrayHasKey('name',$response);

        $this->assertEquals('Product variant was removed',$response['name']);
    }

    /**
     * @test
     */
    public function it_updates_product_variant()
    {
        $this->variants->setBaseUrl($this->url);

        $this->variants->login();

        $this->variants->setFields([
            'idProduct' => 9,
            'name' => 'Jacket'
        ]);

        $response = $this->variants->update(213);

        $this->assertEquals('Product variant was updated', json_decode($response, true)['name']);
    }

    /**
     * @test
     */
    public function it_creates_product_varaint()
    {
        $this->variants->setBaseUrl($this->url);

        $this->variants->login();

        $this->variants->setFields([
            'name' => 'Product test',
            'secondName' => 'Second product name',
            'description' =>'<p>This test product cannot be bought</p>',
            'picture' => '/relative/path/to/picture.jpg',
            'news' => true,
            'secondPrice' => 300,
            'price' => 150,
            'param1' => 'for woman',
            'param2' => 'Yellow',
            'param3' => 'Free shipping',
            'visibleOnHomepage' => true,
            'productNumber' => '999999',
            'previewPicture' => '/relative/path/to/picture.jpg',
            'bestselling' => false,
            'discounted' => true,
            'measuringUnit' => 'Kg',
        ]);

        $response = $this->variants->create();

        $this->assertEquals('Product variant was created', json_decode($response, true)['name']);
    }
}
