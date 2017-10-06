<?php

namespace CoinHiveCaptcha;

use CoinHiveCaptcha\Service\CoinHiveCaptchaService;
use Traversable;
use Zend\Captcha\AbstractAdapter;
use Zend\Validator\Exception;

/**
 * Class CoinHiveCaptcha
 * @package CoinHiveCaptcha
 */
class CoinHiveCaptcha extends AbstractAdapter
{
    /**
     * @var \CoinHiveCaptcha\Service\CoinHiveCaptchaService
     */
    protected $service;

    /**#@+
     * Error codes
     */
    const MISSING_VALUE = 'missingValue';
    const BAD_CAPTCHA   = 'badCaptcha';
    /**#@-*/

    /**
     * Error messages.
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::MISSING_VALUE => 'Missing captcha fields',
        self::BAD_CAPTCHA   => 'Captcha value is wrong. %value%',
    );

    /**
     * @return \CoinHiveCaptcha\Service\CoinHiveCaptchaService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getPubKey()
    {
        return $this->getService()->getPubKey();
    }

    /**
     * @param string $pubKey
     * @return $this
     */
    public function setPubKey($pubKey)
    {
        $this->getService()->setPubtKey($pubKey);

        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getService()->getSecretKey();
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function setSecretKey($secretKey)
    {
        $this->getService()->setSecretKey($secretKey);

        return $this;
    }

    /**
     * @return int
     */
    public function getHashes()
    {
        return $this->getService()->getHashes();
    }

    /**
     * @param int $hashes
     * @return $this
     */
    public function setHashes($hashes)
    {
        $this->getService()->setHashes($hashes);

        return $this;
    }

    /**
     * @return array
     */
    public function getMessageTemplates()
    {
        return $this->messageTemplates;
    }

    /**
     * @param array $messageTemplates
     * @return $this
     */
    public function setMessageTemplates($messageTemplates)
    {
        $this->messageTemplates = $messageTemplates;

        return $this;
    }

    /**
     * Constructor.
     *
     * @param null|array|Traversable $options
     */
    public function __construct($options = null)
    {
        $this->setService(new CoinHiveCaptchaService());

        parent::__construct($options);

        if (!empty($options)) {
            if (array_key_exists('public_key', $options)) {
                $this->getService()->setPubKey($options['public_key']);
            }
            if (array_key_exists('secret_key', $options)) {
                $this->getService()->setSecretKey($options['secret_key']);
            }
            if (array_key_exists('hashes', $options)) {
                $this->getService()->setHashes($options['hashes']);
            }

            $this->setOptions($options);
        }
    }

    /**
     * Generate a new captcha.
     *
     * @return string new captcha ID
     */
    public function generate()
    {
        //Method is in interface, but not required for recaptcha, since google handles the generation
        return '';
    }

    /**
     * Returns true if and only if $value meets the validation requirements.
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value
     * @param mixed $context Param for ZF2.4
     *
     * @return bool
     *
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value, $context = null)
    {
        if (!$value) {
            $this->error(self::MISSING_VALUE);
        }

        $result = $this->getService()->verify($value);

        if (!$result) {
            $this->error(self::BAD_CAPTCHA, $this->getService()->getErrorCode());

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getHelperName()
    {
        return 'captcha/coinhive';
    }
}
