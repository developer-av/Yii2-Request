# duplicate url redirect && language in url Extension for Yii 2

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
            'cookieValidationKey' => '************,
            'languages' => [
                'en' => 'en-US',//default language
                'ru' => 'ru-RU',
            ]
        ],
        'urlManager' => [
            'class' => 'developerav\request\UrlManager',
            'withoutWww' => true, // 301 redirect from www.exemple.com -> exemple.com
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
];
```

Generate Url:

```php
Html::a('test', ['', 'lang_id' => 'uk'])
```