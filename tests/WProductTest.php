<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WProduct;
use PHPUnit\Framework\TestCase;

class WProductTest extends TestCase
{
    private $url;

    private $products;

    public function setUp(): void
    {
        $auth = file_get_contents('stubs/auth.json');
        $auth = json_decode($auth,true);
        $this->url = $auth['url'];

        $this->products = new WProduct($auth['username'],$auth['password'],$auth['apiKey']);

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

    /**
     * @test
     */
    public function it_creates_product()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->setFields([
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

        $response = $this->products->create();

        $this->assertEquals('Product was created', json_decode($response, true)['message']);
    }

    /**
     * @test
     */
    public function it_updates_product()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->setFields([
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

        $response = $this->products->update(213);

        $this->assertEquals('Product was updated', json_decode($response, true)['message']);
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

    /**
     * @test
     */
    public function it_creates_multiple_products()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->setFields([
            [
                'name' => 'Product test 1',
                'secondName' => 'Second product name 1',
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
            ],
            [
                'name' => 'Product test 2',
                'secondName' => 'Second product name 2',
                'description' =>'<p>This test product cannot be bought now</p>',
                'picture' => '/relative/path/to/picture.jpg',
                'news' => true,
                'secondPrice' => 6666,
                'price' => 65420,
                'param1' => 'for woman',
                'param2' => 'Yellow',
                'param3' => 'Free shipping',
                'visibleOnHomepage' => true,
                'productNumber' => '999999',
                'previewPicture' => '/relative/path/to/picture.jpg',
                'bestselling' => false,
                'discounted' => true,
                'measuringUnit' => 'Kg',
            ]
        ]);
        $response = $this->products->createMultiple();

        $this->assertEquals('Products were created', json_decode($response, true)['message']);
    }

    /**
     * @test
     */
    public function it_updates_multiple_products()
    {
        $this->products->setBaseUrl($this->url);

        $this->products->login();

        $this->products->setFields([
            [
                'id' => 426184,
                'name' => 'Product test 1',
                'secondName' => 'Second product name 1 update',
                'secondPrice' => 300,
                'price' => 150,
                'param1' => 'for woman',
                'param2' => 'Yellow',
                'param3' => 'Free shipping',
                'visibleOnHomepage' => false,
                'productNumber' => '999999',
                'bestselling' => false,
                'discounted' => false,
            ],
            [
                'id' => 426185,
                'name' => 'Product test 2',
                'secondName' => 'Second product name 2 update',
                'description' =>'<p>update description test</p>',
                'news' => false,
                'param1' => 'for man',
                'param2' => 'Yellow',
                'param3' => 'Free shipping',
                'visibleOnHomepage' => false,
                'productNumber' => '9999399',
                'bestselling' => false,
                'discounted' => false,
            ]
        ]);

        $response = $this->products->updateMultiple();

        $this->assertEquals('Products were updated', json_decode($response, true)['message']);
    }

    /**
     * @test
     */
    public function it_creates_string_for_massUpdate()
    {
        $this->products->setFieldsAsString([]);

        $this->assertEquals("[]",$this->products->getFields());

        $this->products->setFieldsAsString([[
            'id' => 123456,
            'price' => 86.71,
            'news' => 0,
            'visibleOnHomepage' => 0,
            'discounted'  => false,
            'bestselling' => 0,
            'bazaarEnabled' => 0,
            'hidden' => 0,
            ], [
            'id' => 99999,
            'price' => 37,
            'news' => 0,
            'visibleOnHomepage' => 0,
            'discounted' => false,
            'bestselling' => 0,
            'bazaarEnabled' => 0,
            'hidden' => 0,
            ], [
            'id' =>  6989,
            'price'=> 10.37,
            'news' => 0,
            'visibleOnHomepage' => 0,
            'discounted' =>  false,
            'bestselling' => 0,
            'bazaarEnabled' => 0,
            'hidden' => 0,
            ]]);

        $this->assertEquals('[{"id":123456,"price":86.71,"news":false,"visibleOnHomepage":false,"discounted":false,"bestselling":false,"bazaarEnabled":false,"hidden":false},{"id":99999,"price":37,"news":false,"visibleOnHomepage":false,"discounted":false,"bestselling":false,"bazaarEnabled":false,"hidden":false},{"id":6989,"price":10.37,"news":false,"visibleOnHomepage":false,"discounted":false,"bestselling":false,"bazaarEnabled":false,"hidden":false}]',$this->products->getFields());
    }
}
