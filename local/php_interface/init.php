<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Application;

Loader::includeSharewareModule("htc.twigintegrationmodule");
// Сброс кеша твига при обычном сбросе кеша шаблонов
$request = Application::getInstance()->getContext()->getRequest();
if ($request->getQuery("clear_cache") == "Y") {
    TwigTemplateEngine::clearCacheFiles();
}
Loader::includeModule("lui.kocmo");
Loader::includeModule("catalog");
Loader::includeModule("iblock");
Loader::includeModule("sale");
include_once __DIR__ . '/lib.php';
include $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.kocmo/init.php';
