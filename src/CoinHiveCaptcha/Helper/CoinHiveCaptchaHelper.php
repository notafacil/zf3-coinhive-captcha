<?php

namespace CoinHiveCaptcha\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormInput;

/**
 * Class CoinHiveCaptchaHelper
 * @package CoinHiveCaptcha\Helper
 */
class CoinHiveCaptchaHelper extends FormInput
{
    /**
     * @param ElementInterface $element
     *
     * @return string|FormInput
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function render(ElementInterface $element)
    {
        /** @var \CoinHiveCaptcha\CoinHiveCaptcha $captcha */
        $captcha = $element->getCaptcha();

        $pubKey = $captcha->getPubKey();

        $hashes = $captcha->getHashes();

        $placeholder = "<em>Loading Captcha...<br> If it doesn't load, please disable Adblock!</em>";
        $html = sprintf('<div class="coinhive-captcha" data-key="%s" data-hashes="%s">%s</div>', $pubKey, $hashes, $placeholder);
        //Add the js for the recaptcha api
        $this->view->headScript()->appendFile('https://coinhive.com/lib/captcha.min.js');

        return $html;
    }
}
