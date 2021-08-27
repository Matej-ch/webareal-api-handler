<?php

namespace matejch\webarealApiHandler;

class WCustomers extends WebarealHandler
{
    /**
     * Endpoint for getting list of customers as json or array
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function get(string $endPoint = '/customers')
    {
        return $this->commonCurl($endPoint . $this->query);
    }
}