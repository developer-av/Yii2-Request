<?php

namespace developerav\request\models;

use yii\helpers\ArrayHelper;

/**
 * Description of Lang
 *
 * @author alex
 */
class Lang extends \yii\base\Model {

    public static $current = null;

    public static function findAll()
    {
        return \yii::$app->request->languages;
    }

    static function getCurrent() {
        if (self::$current === null) {
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    static function setCurrent($url = null) {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        \Yii::$app->language = self::$current['local'];
    }

    static function getDefaultLang() {
        reset(\yii::$app->request->languages);
        $key = key(\yii::$app->request->languages);
        return ['local' => ArrayHelper::getValue(\yii::$app->request->languages, $key), 'url' => $key];
    }

    public static function getLangByUrl($url = null) {
        if ($url === null) {
            return null;
        } else {
            $language = (ArrayHelper::getValue(\yii::$app->request->languages, $url) !== null? ['local' => ArrayHelper::getValue(\yii::$app->request->languages, $url), 'url' => $url] : NULL);
            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }

}
