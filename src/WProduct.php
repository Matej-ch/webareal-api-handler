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
            throw new \Exception('ID is missing.');
        }

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Create new product on eshop
     *
     * @return bool|string
     * @throws \Exception
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
     * @param $id
     * @return bool|string
     * @throws \Exception
     */
    public function update($id)
    {
        if(empty($id)) {
            throw new \Exception('ID is missing.');
        }

        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Delete product from eshop with api endpoint
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        if(empty($id)) {
            throw new \Exception('ID is missing.');
        }

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
     * @param $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }
}