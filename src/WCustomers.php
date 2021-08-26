<?php

namespace matejch\webarealApiHandler;

class WCustomers extends WebarealHandler
{
    /**
     * Set to true if you want to return response as array
     * @var bool
     */
    public $asArray = false;

    /**
     * Endpoint for getting list of customers as json or array
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function get(string $endPoint = 'customers')
    {
        if($this->asArray) {
            return json_decode($this->commonCurl($endPoint . $this->query),true);
        }

        return $this->commonCurl($endPoint . $this->query);
    }
}