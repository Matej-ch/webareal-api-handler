<?php

namespace matejch\webarealApiHandler;

use Exception;

/**
 * Product variants are products connected to other product
 *
 */
class WProductVariants extends WebarealHandler
{
    private $endPoint = '/product-variants';

    /**
     * Get list of existing product variants, filter with query
     * Product variants are basically products
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = '/product-variants')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * View detail data about single product variant
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
     * Create new product variant
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
     * Update existing product variant
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
     * Delete product variant
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
     * Set new api end point for use with product variant
     * In case endpoint has changed
     *
     * @param $endPoint
     */
    public function setEndPoint($endPoint): void
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Create multiple variants of product at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function createMultiple(string $endPoint = '/product-variant/mass')
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }

    /**
     * Update multiple variants products at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function updateMultiple(string $endPoint = '/product-variant/mass')
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }
}