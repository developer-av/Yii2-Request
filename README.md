# Request Extension for Yii 2

[![Total Downloads](https://poser.pugx.org/developer-av/yii2-request/downloads)](https://packagist.org/packages/developer-av/yii2-request)
[![License](https://poser.pugx.org/developer-av/yii2-request/license)](https://packagist.org/packages/developer-av/yii2-request)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require developer-av/yii2-request
```

or add

```json
"developer-av/yii2-request": "*"
```

to the require section of your composer.json.

Usage
-----

To use this extension,  simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'request' => [
            'class' => 'developerav\request\Request',
            'cookieValidationKey' => 'E25Mv11DXROHLEpbgJXk9Ju4F8nUvY-F',
            'languages' => [
                'en' => 'en-US',//default language
                'ru' => 'ru-RU',
            ]
        ],
    ],
];
```