<?php

namespace Kocmo\User\Discount;

class UserDiscount
{
    protected $reqData = null;
    protected $arData = null;
    protected $serviceUrl = '';

    public function __construct()
    {
    }

    public function getUserDiscount($discountCartNum)
    {

        $this->request($discountCartNum);

        if (is_array($this->arData)) {
            return $this->arData;
        } else {
            return false;
        }
    }

    private function request($discountCartNum)
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
}
