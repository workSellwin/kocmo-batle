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

    public function getUserDiscount($discountCartNum){

        $this->request($discountCartNum);

        if( is_array($this->arData) ){
            return $this->arData;
        }
        else{
            return false;
        }
    }

    private function request($discountCartNum){//тут будет запрос к сервису

        $this->reqData = '{"CurrentSum":"581",
"CurrentName":"4% от суммы при достижении 500 р.",
"CurrentValue":"4",
"NextSum":"600",
"NextName":"5% от суммы при достижении 600 р.",
"NextValue":"5"} ';

        $this->arData = json_decode($this->reqData, true);

        if( is_array($this->arData) && intval($this->arData['NextSum']) > 0
            && intval($this->arData['CurrentSum']) > 0){
            $this->arData['DiffSum'] = $this->arData['NextSum'] - $this->arData['CurrentSum'];
        }
    }
}