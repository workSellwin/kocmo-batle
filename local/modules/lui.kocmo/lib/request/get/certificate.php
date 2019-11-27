<?php


namespace Lui\Kocmo\Request\Get;


class Certificate extends BaseGet
{
    /**
     * Certificate constructor.
     * @param string $id
     */
    public function __construct(string $id = '')
    {
        parent::__construct('http://kocmo1c.sellwin.by/Kosmo/hs/Kocmo/GetCertificate');
        $this->SetQuery(['id' => $id]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }
}
