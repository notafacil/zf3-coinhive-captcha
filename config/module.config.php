<?php

namespace CoinHiveCaptcha;

return [
    'service_manager' => array(
        'invokeables' => array(
            'CoinHiveCaptcha\Service\CoinHiveCaptchaService' => Service\CoinHiveCaptchaService::class,
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'captcha/coinhive' => Helper\CoinHiveCaptchaHelper::class,
        ),
    ),
];
