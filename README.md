# CoinHiveCaptcha - A ZF2/3 Module for CoinHive.com Captcha 
Simple way to use the coinhive.com captcha
 
## Installation 
### with composer
Just add the following line to your requirements:
```bash
composer require notafacil/zf3-coinhive-captcha
```
and run
```bash
php composer.phar update
```

Since there are problems with the SSL-Cert if you use Adapter\Socket, please install php-curl! 

Then activate the module in your application.config.php :

    ```php
    return array(
        'modules' => array(
            // ...
            'CoinHiveCaptcha',
        ),
        // ...
    );
    ```


## Get your private-key 
To use this service, you have to register at [CoinHive](https://coinhive.com/settings/sites) using your CoinHive Account.

## Usage 
### with Zend\From
Just add the following lines to your form creation:
```
$element = new \Zend\Captcha\Captcha('coinhive-captcha-token');
$element->setCaptcha(new CoinHiveCaptcha\CoinHiveCaptcha(array('secret_key' => 'YOUR_SECRET', 'public_key' => 'YOUR_PUBLIC_KEY', 'hashes' => 512)));
$form->add($element);

```
Remember to add this element to your validationChain as well.

It acts the same way as any other built-in captcha solution.
 
### with ServiceManager 
If you like to implement the view on your own, just use the Service\CoinHiveCaptchaService. It handles the whole communication between your code and the CoinHive API. 

```
$recaptcha = $serviceLocator->get('CoinHiveCaptcha\Service\CoinHiveCaptchaService');

```
