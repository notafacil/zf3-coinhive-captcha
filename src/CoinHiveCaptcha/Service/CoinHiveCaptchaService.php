<?php

namespace CoinHiveCaptcha\Service;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;

/***
 * Class CoinHiveCaptchaService
 * @package CoinHiveCaptcha\Service
 */
class CoinHiveCaptchaService
{
    /**
     *  The URL for to verify an Captcha.
     */
    const VERIFY_SERVER = 'https://api.coinhive.com/token/verify';

    /**
     * @var string
     */
    protected $pubKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var int
     */
    protected $hashes;

    /**
     * @var String Error code from request
     */
    protected $errorCode = null;

    /**
     * @param null $pubKey
     * @param null $secretKey
     * @param int $hashes
     */
    public function __construct($pubKey = null, $secretKey = null, $hashes = 1024)
    {
        $this->pubKey = $pubKey;
        $this->secretKey = $secretKey;
        $this->hashes = $hashes;
    }

    /**
     * @return string
     */
    public function getPubKey()
    {
        return $this->pubKey;
    }

    /**
     * @param string $pubKey
     * @return $this
     */
    public function setPubKey($pubKey)
    {
        $this->pubKey = $pubKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getHashes()
    {
        return $this->hashes;
    }

    /**
     * @param int $hashes
     * @return $this
     */
    public function setHashes($hashes)
    {
        $this->hashes = $hashes;

        return $this;
    }

    /**
     * @return String
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param String $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @param $clientResponse
     * @param null $ip
     *
     * @return bool
     */
    public function verify($clientResponse, $ip = null)
    {
        //send the request to google recaptcha
        $request = $this->sendApiRequest($clientResponse, $ip);
        // and validate it
        return  $this->verifyApiResponse($request);
    }

    /**
     * @param $response
     * @param null $ip
     *
     * @return HttpResponse
     */
    public function sendApiRequest($response, $ip = null)
    {
        $postParams = array(
            'token' => $response,
            'hashes' => $this->getHashes(),
            'secret' => $this->getSecretKey(),
        );

        $httpClient = new HttpClient();

        $httpClient->setAdapter('Zend\Http\Client\Adapter\Curl');
        $request = new HttpRequest();
        $request->setUri(self::VERIFY_SERVER);
        $request->setMethod(HttpRequest::METHOD_POST);
        $request->getPost()->fromArray($postParams);

        return $httpClient->send($request);
    }

    /**
     * @param HttpResponse $response
     *
     * @return bool
     */
    public function verifyApiResponse(HttpResponse $response)
    {
        $body = $response->getBody();
        $content = json_decode($body, true);

        //get the status
        if ($content['success'] === true) {
            return true;
        } else {
            return false;
        }
    }
}
