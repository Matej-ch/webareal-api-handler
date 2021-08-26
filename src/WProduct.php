<?php

namespace matejch\webarealApiHandler;

class WProduct extends WebarealHandler
{

    /**
     * ID of product you want to work with
     *
     * This id is given to products by webareal
     *
     * It can be retrieved from webareal csv for products, or from list of products from api request
     *
     * @var mixed
     */
    private $id;

    /**
     * Set to true if you want to return response as array
     * @var bool
     */
    public $asArray = false;

    /**
     * Endpoint for getting list of products as json or array
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function get(string $endPoint = 'products')
    {
        if($this->asArray) {
            return json_decode($this->commonCurl($endPoint . $this->query),true);
        }

        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * View data about single product on eshop
     * Requires id of product
     *
     * @param string $endPoint
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function view(string $endPoint = 'product')
    {
        if(empty($this->id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }

        if($this->asArray) {
            return json_decode($this->commonCurl($endPoint . '/' . $this->id),true);
        }

        return $this->commonCurl($endPoint . '/' . $this->id);
    }

    public function create(string $endPoint = 'product')
    {
        if(empty($this->id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }
    }

    public function update(string $endPoint = 'product')
    {
        if(empty($this->id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }
    }

    public function delete(string $endPoint = 'product')
    {
        if(empty($this->id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
}