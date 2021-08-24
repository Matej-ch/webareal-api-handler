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
    private $bearer;

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
     * Login into api
     *
     * @param string $endPoint
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
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        var_dump($httpcode);
        var_dump($response);
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
                "Authorization: Bearer $this->bearer"
            ]
        ]);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($httpcode);
        var_dump($response);

    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}