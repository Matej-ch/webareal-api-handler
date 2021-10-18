<?php

namespace matejch\webarealApiHandler;

use Exception;

class WProduct extends WebarealHandler
{
    private $endPoint = '/product';

    /**
     * Endpoint for getting list of products
     * Endpoint for list of products is different from others end points for product
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function get(string $endPoint = '/products')
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
     * Set new api end point for use with product
     * In case endpoint has changed
     *
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint): void
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Create multiple products at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function createMultiple(string $endPoint = '/products/mass')
    {
        $this->addCurlOptions([CURLOPT_POST => true]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }

    /**
     * Update multiple products at once
     *
     * @param string $endPoint
     * @return array|bool|string
     * @throws Exception
     */
    public function updateMultiple(string $endPoint = '/products/mass')
    {
        $this->addCurlOptions([CURLOPT_CUSTOMREQUEST => "PUT"]);
        $this->addCurlOptions([CURLOPT_POSTFIELDS => $this->fields]);

        return $this->commonCurl($endPoint);
    }

    public function booleanAttributes(): array
    {
        return [
            'news',
            'visibleOnHomepage',
            'bestselling',
            'discounted',
            'discussionEnabled',
            'freeShippingEnabled',
            'bazaarEnabled',
            'unsaleable',
            'hidden',
            'facebookEnabled',
            'zboziczUnfeatured',
            'zboziczFirmycz'

        ];
    }

    /**
     * New better function to prepare data, when updating multiple products at once
     *
     * Parameter $products is array of products where keys should be names of updatable attributes allowed in webareal api
     *
     * @param array $products
     */
    public function setFieldsAsString(array $products): void
    {
        $productsStrings = [];
        foreach ($products as $product) {
            $productRow = '{';
            $i = 1;
            $count = count($product);
            foreach ($product as $attrName => $attrValue) {
                $val = $attrValue;
                if (in_array($attrName, $this->booleanAttributes(), true)) {
                    $val = $attrValue ? 'true' : 'false';
                }

                if ($count === $i) {
                    $productRow .= "\"$attrName\":$val";
                } else {
                    $productRow .= "\"$attrName\":$val,";
                }
                $i++;
            }

            $productRow .= '}';
            $productsStrings[] = $productRow;
        }

        $productsStrings = implode(",", $productsStrings);

        $this->fields = "[" . $productsStrings . "]";
    }
}