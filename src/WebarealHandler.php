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
    private $baseUrl = 'https://api.premium-wask.cz';

    /**
     * Username is obtained from webareal
     *
     * @var string
     */
    private $username;

    /**
     * Password is obtained on webareal
     *
     * @var string
     */
    private $password;

    /**
     * Api key from webareal
     *
     * @var string
     */
    private $apiKey;

    /**
     * This value is returned after login in response
     *
     * @var string
     */
    private $bearerToken;

    /**
     * If you want to test api on development server without certificate, set enableDev to true
     * @var bool
     */
    private $enableDev = false;

    /**
     * @var string
     */
    public $responseCode;

    public function __construct($username, $password,$apiKey)
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
        $this->baseUrl = rtrim($baseUrl,'/');
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
    public function login(string $endPoint = 'login')
    {
        $ch = curl_init();

        curl_setopt_array($ch,[
            CURLOPT_URL => $this->baseUrl.'/'.$endPoint,
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

            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $this->responseCode = $httpCode;

        if($httpCode !== 200) {
            throw new \Exception("Response code is $httpCode");
        }

        $decodedResponse = json_decode($response,true);
        $this->bearerToken = $decodedResponse['token'];
    }

    /**
     * Test if user is logged in
     *
     * @param string $endPoint
     */
    public function test(string $endPoint = 'test'): void
    {
        $ch = curl_init();

        curl_setopt_array($ch,[
            CURLOPT_URL => $this->baseUrl.'/'.$endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => [
                "X-Wa-api-token: $this->apiKey",
                "Authorization: Bearer $this->bearerToken"
            ]
        ]);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($httpcode);
        var_dump($response);

    }

    /**
     * @param bool $enableDev
     */
    public function setEnableDev(bool $enableDev): void
    {
        $this->enableDev = $enableDev;
    }

    /**
     * @return string
     */
    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }
}