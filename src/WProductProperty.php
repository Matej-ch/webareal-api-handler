<?php

namespace matejch\webarealApiHandler;

use Exception;

class WProductProperty extends WebarealHandler
{
    private $endPoint = '/product-properties';

    /**
     * Get list of existing properties, filter with query
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = '/product-properties')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * Create one product property
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
     * View detail data about single product property
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
     * Update existing product property
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
     * Delete product property
     *
     * @param int $id
     * @return array|bool|string
     * @throws Exception
     */
    public function delete(int $id)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "DELETE"]);

        return $this->commonCurl($this->endPoint . '/' . $id);
    }

    /**
     * Set new api end point for use with product property
     * In case endpoint has changed
     *
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint): void
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Create multiple products properties at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function createMultiple(string $endPoint = '/product-property/mass')
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }

    /**
     * Update multiple product properties at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function updateMultiple(string $endPoint = '/product-property/mass')
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }

}