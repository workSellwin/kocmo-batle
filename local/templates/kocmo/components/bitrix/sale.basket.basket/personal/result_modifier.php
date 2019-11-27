<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */

/** @var array $arResult */

use Bitrix\Main;
use Lui\Kocmo\Request\Basket;

$count = 0;
$arItems = [];
if ($arResult['GRID']['ROWS']) {
    $count = count($arResult['GRID']['ROWS']);
    $arItems = $arResult['GRID']['ROWS'];
}


$ob = new Basket();
$ar1c = $ob->Run();
$arItemsNew = [];
foreach ($arItems as $arItem) {
    $obProd = new \Lui\Kocmo\Product($arItem);
    $arItem = $obProd->GetDataBasketMain();
    $arItemsNew[$arItem['PRODUCT_XML_ID']] = $arItem;
}
if ($ar1c['goods']) {
    $ar1c['goods'] = array_column($ar1c['goods'], null, 'UID');
}

foreach ($arItemsNew as $key => &$arItem) {
    $ar1cItem = $ar1c['goods'][$key];
    $arItem['DISCOUNT'] = $ar1cItem['DISCOUNT'];
    if ($arItem['DISCOUNT']) {
        $count = $ar1cItem['COUNT'];
        $price = $ar1cItem['PRICE'];
        $sum = $ar1cItem['SUMM'];
        $allDiscount = 0;
        foreach ($arItem['DISCOUNT'] as &$arDiscount) {
            $allDiscount += $arDiscount['VALUE'];
            $arDiscount['PERCENT'] = round($arDiscount['VALUE'] / $sum, 2)*100;
        }
        $priceDiscountOne = round($allDiscount / $count, 2);
        $arPriceOld = $price;
        $arPriceNew = $price - $priceDiscountOne;
        $arItem['PRICE_OLD'] = $arPriceOld;
        $arItem['PRICE_NEW'] = $arPriceNew;
    }
}

$arData = [
    'COUNT' => $count,
    'ITEMS' => $arItemsNew,
    'SUM' => $arResult['allSum'],
];


$arResult = $arData;


