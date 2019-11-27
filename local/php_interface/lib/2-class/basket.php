<?php

use Bitrix\Sale;
use InitMainTrait;

class Basket
{

    use InitMainTrait;
    public static $BASKET;

    public function __construct()
    {
        $this->includeModules();
        self::$BASKET = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    }

    /**
     * @param $PRICE_ID
     * @param bool $QUANTITY
     * @param array $arRewriteFields
     * @param array $arProductParams
     * @return mixed
     * Добавление товара в корзину в старом варианте
     */
    public static function addBasketOld($PRICE_ID, $QUANTITY = false, $arRewriteFields = [], $arProductParams = []){
        $e = Add2BasketByProductID($PRICE_ID, $QUANTITY, $arRewriteFields, $arProductParams);
        if ($e)return $e;
    }

    /**
     * @param $PRICE_ID
     * @param $QUANTITY
     * @return bool
     * Добавление товара в корзину
     */
    public static function addBasket($PRICE_ID, $QUANTITY){
        $fields = [
            'PRODUCT_ID' => $PRICE_ID, // ID товара, обязательно
            'QUANTITY' => $QUANTITY, // количество, обязательно
        ];
        $r = \Bitrix\Catalog\Product\Basket::addProduct($fields);
        if ($r->isSuccess()) {
            return true;
        }else{
            return false;
        }
    }

    public static function getProductBasket(){

        $basketRes = Sale\Internals\BasketTable::getList(array(
            'filter' => array(
                'FUSER_ID' => Sale\Fuser::getId(),
                'ORDER_ID' => null,
                'LID' => SITE_ID,
                'CAN_BUY' => 'Y',
            )
        ));
        $res = array();
        while ($item = $basketRes->fetch()) {
            $res[]=$item;
        }
        return $res;
    }

}