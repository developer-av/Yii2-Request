<?php

namespace developerav\request;

use \Yii;
use \yii\web\UrlManager as BaseUrlManager;
use \yii\helpers\Url;
use \developerav\request\models\Lang;

class UrlManager extends BaseUrlManager {

    /**
     * @var boolean когда true redirect с www на без www
     */
    public $withoutWww = false;

    /**
     * @var boolean когда true redirect с без / в конце на /
     */
    public $SlashTheEnd = true;

    public function parseRequest($request) {
        if ($this->suffix == '' && $this->SlashTheEnd == true)
            $this->suffix = '/';
        $this->indexFix();
        if ($this->enablePrettyUrl) {
            $pathInfo = $request->getPathInfo();
            /* @var $rule UrlRule */
            foreach ($this->rules as $rule) {
                if (($result = $rule->parseRequest($this, $request)) !== false) {
                    $this->doubleUrlFix($result);
                    $this->wwwFix();
                    return $result;
                }
            }

            if ($this->enableStrictParsing) {
                return false;
            }

            Yii::trace('No matching URL rules. Using default URL parsing logic.', __METHOD__);

            $suffix = (string) $this->suffix;
            if ($suffix !== '' && $pathInfo !== '') {
                $n = strlen($this->suffix);
                if (substr_compare($pathInfo, $this->suffix, -$n, $n) === 0) {
                    $pathInfo = substr($pathInfo, 0, -$n);
                    if ($pathInfo === '') {
//                         suffix alone is not allowed
                        return false;
                    }
                } else {
                    // suffix doesn't match
                    $this->doubleUrlFix([$pathInfo, []]);
                    return false;
                }
            }

            $url = [$pathInfo, []];
            $this->doubleUrlFix($url);
            $this->wwwFix();
            return $url;
        } else {
            Yii::trace('Pretty URL not enabled. Using default URL parsing logic.', __METHOD__);
            $route = $request->getQueryParam($this->routeParam, '');
            if (is_array($route)) {
                $route = '';
            }
            return [(string) $route, []];
        }
    }

    /**
     * Редирект на без www
     * @param string $url
     * @return string|bool $url|redirect
     */
    public function wwwFix($url = false) {
        if (strpos(Yii::$app->request->hostInfo, 'www.') !== false && $this->withoutWww == true) {
            if ($url) {
                return str_replace('www.', '', Yii::$app->request->hostInfo) . $url;
            } else {
                $url = str_replace('www.', '', Yii::$app->request->hostInfo) . Yii::$app->request->url;
                Yii::$app->getResponse()->redirect($url, 301);
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $url);
                Yii::$app->end();
            }
        } else {
            return $url;
        }
    }

    /**
     * Убирает дубли страниц
     * @param type $url
     */
    private function doubleUrlFix($url) {
        $url[0] = trim($url[0], '/');
        $url = Url::to(array_merge(['/' . $url[0]], array_merge($url[1], Yii::$app->request->get())));
        if ($url != Url::to()) {
            $url = $this->wwwFix($url);
            Yii::$app->getResponse()->redirect($url, 301);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            Yii::$app->end();
        }
    }

    /**
     * убирает дубли через index.php
     */
    public function indexFix() {
        if (strpos(Yii::$app->request->url, Yii::$app->request->scriptUrl) !== FALSE && $this->showScriptName == false) {
            $url = str_replace(Yii::$app->request->scriptUrl, '', Yii::$app->request->url);
            if (strpos($url, '/') !== 0) {
                $url = '/' . $url;
            }
            $this->doubleUrlFix([$url, []]);
            $url = $this->wwwFix($url);
            Yii::$app->getResponse()->redirect($url, 301);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            Yii::$app->end();
        }
    }

    public function createUrl($params) {

        if (isset($params['lang_id'])) {

            //Если указан идентификатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Lang::getLangByUrl($params['lang_id']);
            if ($lang === null) {
                $lang = Lang::getDefaultLang();
            }
            unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Lang::getCurrent();
        }

        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);

        $url = preg_replace('#^'.\Yii::$app->homeUrl.'#', '', $url);

//        var_dump($url);
//        var_dump(\Yii::$app->homeUrl.$lang['url'].'/'.$url);die;
        //Добавляем к URL префикс - буквенный идентификатор языка
        if ($lang != Lang::getDefaultLang()) {
//            if ($url == '') {
//                return '/' . $lang['url'] . '/';
//            } else {
//                return '/' . $lang['url'] . $url;
//            }
            return \Yii::$app->homeUrl.$lang['url'].'/'.$url;
        } else {
            if ($url == '') {
                return \Yii::$app->homeUrl;
            }
            return \Yii::$app->homeUrl.$url;
        }
    }

}
