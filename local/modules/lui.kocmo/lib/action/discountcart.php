<?php


namespace Lui\Kocmo\Action;


use Action;
use Lui\Kocmo\Interfaces\ActionsInterfaces;

class DiscountCart implements ActionsInterfaces
{
    private $arData = null;
    private $actionObj = null;

    public function __construct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/discount/lib.php';

        $this->actionObj = new Action();
    }

    public function getDiscount($param){

        $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];
        $param = json_decode($param, true);

        if(!isset($param['cart']) ){
            $returnVal["ERRORS"][] = 'no cart';
            $returnVal["SUCCESS"] = 0;
            return $returnVal;
        }
        $this->discountRequest($param['cart']);

        if($this->arData){
            $returnVal["VALUE"] = $this->arData;
            $returnVal["SUCCESS"] = 1;
        }
        else{
            $returnVal["ERRORS"][] = 'no answer';
            $returnVal["SUCCESS"] = 0;
        }
        return $returnVal;
    }

    public function updateCart($param){

        $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];
        $param = json_decode($param, true);

        if( !empty($param['code']) ){
            $userData = $this->getUserData($param['code']);

            if(is_array($userData)){

                $userData['ACTION'] = 'DiscountUpdate';
                $userData = array_merge($userData, $param);
                $arResult = $this->actionObj->Run($userData);

                if( is_array($arResult)){
                    $returnVal["VALUE"] = $userData;
                    $returnVal["SUCCESS"] = 1;
                }
            }

            if($returnVal["SUCCESS"] !== 1) {
                $returnVal["SUCCESS"] = 0;
            }
        }
        else{
            $returnVal["ERRORS"][] = 'empty code';
            $returnVal["SUCCESS"] = 0;
        }

        return $returnVal;
    }

    private function discountRequest($discountCartNum)
    {//тут будет запрос к сервису

        $this->reqData = '{
"CurrentSum": "206.36",
"CurrentName": "4% на номенклатуру сегмента Вид номенклатуры Товар (Сумма продажи по ДК не менее 200 руб.)",
"CurrentValue": "4",
"NextSum": "400.00",
"NextName": "5% на номенклатуру сегмента Вид номенклатуры Товар (Сумма продажи по ДК не менее 400 руб.)",
"NextValue": "5"
}';

        $this->arData = json_decode($this->reqData, true);

        if (is_array($this->arData) && intval($this->arData['NextSum']) > 0
            && intval($this->arData['CurrentSum']) > 0) {
            $this->arData['DiffSum'] = $this->arData['NextSum'] - $this->arData['CurrentSum'];
        }
    }

    private function getUserData($code){

        //$ob = new Action();
        $arResult = $this->actionObj->Run(['ACTION' => 'Discount', 'code' => $code]);

        return is_array($arResult) ? $arResult : false;
    }

    public function Available()
    {
        return ['getDiscount', 'updateCart'];
    }
}