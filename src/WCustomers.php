<?php

namespace matejch\webarealApiHandler;

class WCustomers extends WebarealHandler
{
    /**
     * Query string for api
     * @var string
     */
    public $query = '';

    /**
     * Set to true if you want to return response as array
     * @var bool
     */
    public $asArray = false;

    /**
     * Available options are limit(int), offset(int), sortBy(string), sortDirection(string), name(string)
     *
     * format of searchBy array example:
     * [
     *  'limit' => 10,
     *  'offset' => 10,
     *  'sortBy' => 'id' //only id is supported
     *  'sortDirection' => 'desc', //Possible values: asc, desc
     *  'findBy' => 'id', // only id is supported
     *  'searchedString' => 'value you are searching for'
     * ]
     * @param array $searchBy
     */
    public function searchBy(array $searchBy = []): void
    {
        if (!empty($searchBy)) {
            if (isset($searchBy['findBy'])) {
                $searchBy["findBy[{$searchBy['findBy']}]"] = $searchBy['searchedString'];
                unset($searchBy['findBy'], $searchBy['searchedString']);
            }

            $this->query = "?" . http_build_query($searchBy);
        }
    }

    /**
     * Endpoint for getting list of customers as json
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