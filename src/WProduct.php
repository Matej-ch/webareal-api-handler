<?php

namespace matejch\webarealApiHandler;

class WProduct extends WebarealHandler
{
    private $fields;

    private $endPoint = '/product';

    /**
     * Endpoint for getting list of products
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function get(string $endPoint = 'products')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * View data about single product on eshop
     * Requires id of product
     *
     * @throws \Exception
     */
    public function view($id)
    {
        if(empty($id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    public function create()
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
    }

    public function update($id)
    {
        if(empty($id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }

        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
    }

    /**
     * Delete product from eshop with api endpoint
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        if(empty($id)) {
            throw new \Exception('ID is missing. Make sure you set id with method setId($id).');
        }

        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "DELETE"]);

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * Set new endPoint for work with product
     * In case endpoint has changed
     *
     * @param $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }
}