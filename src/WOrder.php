<?php

namespace matejch\webarealApiHandler;

use Exception;

class WOrder extends WebarealHandler
{
    private $fields;

    private $endPoint = '/order';
    
    /**
     * Endpoint for getting list of order
     * Endpoint for list of orders is different from others end points for order
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = '/orders')
    {
        return $this->commonCurl($endPoint . $this->query);
    }

    /**
     * Order has special API endpoint for getting states of order
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function states(string $endPoint = '/states')
    {
        return $this->commonCurl($this->endPoint .$endPoint);
    }

    /**
     * View detail data about order, including products
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
     * Update existing order on eshop
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
     * Delete order from webareal eshop
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
     * Set new api end point for use with order
     * In case endpoint has changed
     *
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint): void
    {
        $this->endPoint = $endPoint;
    }
}