<?php


namespace Kocmo\Exchange\Bx;


class End
{
    public function update() : bool{

        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable('kocmo_exchange_data');
        $connection->truncateTable('kocmo_exchange_product_image');

        Bitrix\Main\Config\Option::set('kocmo.exchange', 'PRODUCT_LAST_UID', '');
        Bitrix\Main\Config\Option::set('kocmo.exchange', 'OFFER_LAST_UID', '');

        return true;
    }
}