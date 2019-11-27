<?php

namespace Lui\Kocmo\Request\Get;

class Actions extends BaseGet
{
    /**
     * Actions constructor.
     * @param string $shop
     * @throws \Exception
     */
    public function __construct(string $shop = '')
    {
        parent::__construct('http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetActions');
        $this->SetQuery(['shop' => $shop]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }
}
