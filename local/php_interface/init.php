<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;


Loader::includeSharewareModule("htc.twigintegrationmodule");
// Сброс кеша твига при обычном сбросе кеша шаблонов
$request = Application::getInstance()->getContext()->getRequest();
if ($request->getQuery("clear_cache") == "Y") {
    TwigTemplateEngine::clearCacheFiles();
}

Loader::includeModule("lui.kocmo");
Loader::includeModule("catalog");
Loader::includeModule("iblock");
Loader::includeModule("sale");
include_once __DIR__ . '/lib.php';
include $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.kocmo/init.php';


AddEventHandler('main', 'OnAdminContextMenuShow', 'OrderDetailAdminContextMenuShow');
function OrderDetailAdminContextMenuShow(&$items)
{
    global $APPLICATION;
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_list_admin.php' && $_REQUEST['IBLOCK_ID'] == 5) {
        $button = array(
            "TEXT" => "Обновить акции из 1с",
            "LINK" => $APPLICATION->GetCurPageParam("ACTIONS-UPDATE=Y", ['ACTIONS-UPDATE']),
            "TITLE" => "Обновить акции из 1с",
            "ICON" => "btn_new",
        );
        array_push($items, $button);
    }
}

AddEventHandler('main', 'OnPageStart', 'OnPageStartActionUpdate');

function OnPageStartActionUpdate()
{
    if ($_REQUEST['ACTIONS-UPDATE'] == 'Y') {
        $obS = new \Lui\Kocmo\Request\Set\Actions();
        try {
            $obS->Update();
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            AddMessage2Log($e->getMessage());
        } catch (\Exception  $e) {
            AddMessage2Log($e->getMessage());
        }
    }
}


AddEventHandler('sale', 'OnSalePayOrder', 'OnSalePayOrderActionUpdateEGift');
function OnSalePayOrderActionUpdateEGift($order_id, $status)
{
    if ($status == 'Y') {
        CModule::IncludeModule("sale");
        CModule::IncludeModule("iblock");
        $order = new MyOrder();
        $order = $order->GetOrder($order_id);
        $propertyCollection = $order->getPropertyCollection();
        $arProperty = $propertyCollection->getArray();
        $arProperty = array_column($arProperty['properties'], 'VALUE', 'CODE');

        //проверка на сертификат
        if (count($arProperty['EGIFT']) == 1 && in_array('Y', $arProperty['EGIFT'])) {

            $payment = 8;
            $price_order = $order->getPrice(); // Сумма заказа
            $isPaid = $order->isPaid(); // true, если оплачен
            $price_paid = $order->getSumPaid(); // Оплаченная сумма

            if ($isPaid) {
                //Сумма заказа = Оплаченная сумма
                if ($price_order == $price_paid) {
                    /*$order->setBasket($basket);
                    $basket = $order->getBasket();*/
                    $paymentIds = $order->getPaymentSystemId();
                    if (in_array($payment, $paymentIds)) {

                        $basket = $order->getBasket();
                        foreach ($basket as $basketItem) {
                            $id = $basketItem->getProductId();
                        }

                        $EGift = new EGift($id);
                        //получение номера E-Gift
                        // не реализовано

                        $gui = $EGift->OB['PROPERTY']['GUI_SERTIFIKAT'];
                        $num_egift = rand(1000000, 9999999);

                        $html = $EGift->GetHtmlEmail();

                        if ($num_egift) {

                            CIBlockElement::SetPropertyValuesEx($id, false, ['OPLACHEN' => 1601, 'SHTRIH_KOD' => $num_egift]);
                            CIBlockElement::SetPropertyValuesEx($id, false, ['DATE' => date('d.m.Y')]);

                            $arPropCode = ["SHTRIH_KOD", "OPLACHEN", "EMAIL", 'EMAIL_SENT', 'DATE'];
                            $PROP = [];
                            foreach ($arPropCode as $val) {
                                $prop = CIBlockElement::GetProperty(12, $id, ["sort" => "asc"], ["CODE" => $val]);
                                if ($ar_props = $prop->Fetch()) {
                                    $PROP[$ar_props['CODE']] = $ar_props;
                                }
                            }

                            $result = (strtotime($PROP['DATE']['VALUE']) <= strtotime(date('d.m.Y')));
                            if ($result || $PROP['DATE']['VALUE'] == '') {

                                //отпровляем сертификат на указанный email
                                if ($PROP['EMAIL']['VALUE']) {

                                    $arEventFields = [
                                        "HTML" => $html,
                                        "EMAIL_TO" => $PROP['EMAIL']['VALUE'],
                                    ];

                                    $sent_email = CEvent::Send('E-GIFT', 's1', $arEventFields);
                                }

                                if ($sent_email) {
                                    CIBlockElement::SetPropertyValuesEx($id, false, ['EMAIL_SENT' => 1602]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}