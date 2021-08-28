<?php

namespace matejch\webarealApiHandler;

use Exception;

class WPropertyValue extends WebarealHandler
{
    private $fields;

    private $endPoint = '/product-property-values';

    /**
     * Get list of property values
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = '/product-property-values')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * Create property value
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
     * Update property value
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
     * Show detail of property value
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
     * Delete one property value
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
     * Set fields as associative array
     *
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = json_encode($fields);
    }

    /**
     * Set new api end point for use with product variant
     * In case endpoint has changed
     *
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint): void
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Create multiple property values at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function createMultiple(string $endPoint = '/product-properties-values/mass')
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }
}