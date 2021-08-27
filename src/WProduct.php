<?php

namespace matejch\webarealApiHandler;

use Exception;

class WProduct extends WebarealHandler
{
    private $fields;

    private $endPoint = '/product';

    /**
     * Endpoint for getting list of products
     * Endpoint for list of products is different from others end points for product
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = 'products')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * View data about single product on eshop
     * Requires id of product
     *
     * @param int $id
     * @return array|bool|string
     * @throws Exception
     */
    public function view(int $id)
    {
        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Create new product on eshop
     *
     * @return array|bool|string
     * @throws Exception
     */
    public function create()
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($this->endPoint);
    }

    /**
     * Update existing product on eshop
     *
     * @param int $id
     * @return array|bool|string
     * @throws Exception
     */
    public function update(int $id)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Delete product from eshop with api endpoint
     *
     * @throws Exception
     */
    public function delete(int $id)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "DELETE"]);

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Set fields as associative array
     *
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = json_encode($fields);
    }

    /**
     * Set new api end point for use with product
     * In case endpoint has changed
     *
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint): void
    {
        $this->endPoint = $endPoint;
    }
}