<?php


namespace Lui\Kocmo\Request;


use Bitrix\Sale;
use Bitrix\Main\Loader;

class Basket extends Base
{

    protected $obBasket;
    protected $arCache = [];

    public function __construct()
    {
        $url = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetBasket';
        $arParams = ['json' => ''];
        parent::__construct($url, $arParams);
        $this->SetBasket();
        $this->GetCache();
    }

    protected function GetCacheKey()
    {
        return 'Basket-Request-arCache';
    }

    protected function SetCacheData($key, $data)
    {
        $this->arCache[$key] = $data;
        $this->SetCache();
    }

    protected function GeCacheData($key)
    {
        return $this->arCache[$key];
    }

    protected function GetCache()
    {
        $this->arCache = $_SESSION[$this->GetCacheKey()];
    }

    protected function SetCache()
    {
        $_SESSION[$this->GetCacheKey()] = $this->arCache;
    }

    /**
     * @return array;
     */
    public function Run()
    {
        return $this->GetDiscount();
    }


    public function SetBasket()
    {
        $this->obBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
    }

    protected function GetStructure()
    {
        return [
            'card' => '000223',
            'shop' => '554e5045-aa97-11e8-a216-00505601048d',
            'goods' => [
                [
                    'UID' => '8e1e5ee4-aaac-11e8-a216-00505601048d',
                    'COUNT' => '10',
                    'SUMM' => '125'
                ]
            ],
        ];
    }


    public function GetDiscount()
    {
        $arReqest = $this->GetStructure();
        $arReqest['goods'] = $this->SetGoods();
        return $this->Send($arReqest);
    }

    protected function Send(array $arRequest)
    {
        Loader::includeSharewareModule('kocmo.exchange');
        $json = json_encode($arRequest);
        $hash = $this->GetHash($json);
        if (!$data = $this->GeCacheData($hash)) {
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $this->url, [
                    'query' => ['json' => $json]
                ]);
                $data = $res->getBody();
                $data = json_decode($data, true);
            } catch (\Exception $e) {
                $data = [
                    'ERROR' => [$e->getMessage()],
                ];
            }
            $this->SetCacheData($hash, $data);
        }
        return $data;
    }


    public function GetHash($str)
    {
        return md5($str);
    }

    protected function SetGoods()
    {
        $ob = $this->obBasket;
        $arResult = [];
        foreach ($ob as $basketItem) {
            $arResult[] = $this->SetGood($basketItem);
        }
        return $arResult;
    }


    protected function SetGood(Sale\BasketItem $basketItem)
    {
        $arRequest = [
            'UID' => $basketItem->getField('PRODUCT_XML_ID'),
            'COUNT' => $basketItem->getQuantity(),
            'SUMM' => $basketItem->getFinalPrice(),
        ];
        return $arRequest;
    }

}
