<?php

namespace matejch\webarealApiHandler;

class WebarealHandler
{
    /**
     * Each system has own API subdomain
     *
     * Use the right subdomain according system you use
     *
     * List of subdomains https://webareal.docs.apiary.io/#
     *
     * @var string
     */
    protected $baseUrl = 'https://api.premium-wask.cz';

    /**
     * Username is obtained from webareal
     *
     * @var string
     */
    protected $username;

    /**
     * Password is obtained on webareal
     *
     * @var string
     */
    protected $password;

    /**
     * Api key from webareal
     *
     * @var string
     */
    protected $apiKey;

    /**
     * This value is returned after login in response
     *
     * @var string
     */
    protected $bearerToken;

    /**
     * @var array
     */
    protected $curlOptions = [];

    /**
     * @var string
     */
    public $lastResponseCode;

    /**
     * Query string for api, searching list of products, customers, orders
     * @var string
     */
    public $query = '';

    /**
     * Set to true if you want to return response as array
     * @var bool
     */
    public $asArray = false;

    public function __construct($username, $password, $apiKey)
    {
        $this->username = $username;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Login into api
     *
     * @param string $endPoint
     * @throws \Exception
     */
    public function login(string $endPoint = '/login'): void
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $this->baseUrl . $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "{
                \"username\": \"$this->username\",
                \"password\": \"$this->password\"
                }",
            CURLOPT_HTTPHEADER => [
                "X-Wa-api-token: $this->apiKey"
            ],
        ];

        curl_setopt_array($ch, array_replace($options, $this->curlOptions));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $this->lastResponseCode = $httpCode;

        if ($httpCode !== 200) {
            throw new \Exception("Error: Response code is $httpCode");
        }

        $decodedResponse = json_decode($response, true);
        $this->bearerToken = $decodedResponse['token'];
    }

    /**
     * Test if user is logged in
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function test(string $endPoint = '/test')
    {
        return $this->commonCurl($endPoint);
    }

    /**
     * Retrieves info about api including limit for user, actual request by user, and whether is blocked
     *
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    public function apiInfo(string $endPoint = '/api-info')
    {
        return $this->commonCurl($endPoint);
    }

    /**
     * @return string
     */
    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function addCurlOptions(array $options): void
    {
        $this->curlOptions = array_replace($this->curlOptions,$options);
    }

    /**
     * @param string $endPoint
     * @return bool|string
     * @throws \Exception
     */
    protected function commonCurl(string $endPoint)
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $this->baseUrl . $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => [
                "X-Wa-api-token: $this->apiKey",
                "Authorization: Bearer $this->bearerToken"
            ],
        ];

        curl_setopt_array($ch, array_replace($options, $this->curlOptions));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $this->lastResponseCode = $httpCode;

        if ($httpCode !== 200) {
            throw new \Exception("Error: Response code is $httpCode");
        }

        if($this->asArray) {
            $response = json_decode($response,true);
        }

        return $response;
    }

    /**
     * Available options are limit(int), offset(int), sortBy(string), sortDirection(string), name(string)
     *
     * format of searchBy array example:
     * [
     *  'limit' => 10,
     *  'offset' => 10,
     *  'sortBy' => 'id' //id or changedAt
     *  'sortDirection' => 'desc', //Possible values: asc, desc
     *  'findBy' => 'id', // id , name , productNumber
     *  'searchedString' => 'value you are searching for'
     * ]
     * @param array $searchBy
     */
    public function searchBy(array $searchBy = []): void
    {
        if (isset($searchBy['findBy'])) {
            $searchBy["findBy[{$searchBy['findBy']}]"] = $searchBy['searchedString'];
            unset($searchBy['findBy'], $searchBy['searchedString']);
        } else {
            unset($searchBy['searchedString']);
        }

        if (!empty($searchBy)) {
            $this->query = "?" . http_build_query($searchBy);
        }
    }
}