<?php

namespace matejch\webarealApiHandler;

class WOrderProduct extends WebarealHandler
{
    private $fields;

    private $endPoint = '/order';

    /**
     * Update multiple products on order, never used this functionality,
     * but I believe you need to set idCodelist, but nowhere it says how to set it, it's probably an array
     *
     * @param int $orderID
     * @return array|bool|string
     * @throws \Exception
     */
    public function updateMultiple(int $orderID)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($this->endPoint."/$orderID/products/mass");
    }

    /**
     * Update one product on one order
     *
     * @param int $orderID
     * @param int $productID
     * @return array|bool|string
     * @throws \Exception
     */
    public function update(int $orderID, int $productID)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($this->endPoint . "/$orderID/product/$productID");
    }

    /**
     * Delete product from order
     *
     * @param int $orderID
     * @param int $productID
     * @return array|bool|string
     * @throws \Exception
     */
    public function delete(int $orderID, int $productID)
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "DELETE"]);

        return $this->commonCurl($this->endPoint . "/$orderID/product/$productID");
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