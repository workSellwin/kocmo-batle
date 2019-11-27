<?php


namespace Lui\Kocmo\Data;


class Base
{
    /**
     * Base constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock'))
            throw new  \Exception('No Module IBlock');
    }
}
