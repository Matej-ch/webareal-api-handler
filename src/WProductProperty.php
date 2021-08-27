<?php

namespace matejch\webarealApiHandler;

use Exception;

class WProductProperty extends WebarealHandler
{
    private $fields;

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
     * Set fields as associative array
     *
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = json_encode($fields);
    }

    /**
     * Set new api end point for use with product property
     * In case endpoint has changed
     *
     * @param $endPoint
     */
    public function setEndPoint($endPoint): void
    {
        $this->endPoint = $endPoint;
    }

}