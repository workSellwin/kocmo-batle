<?php

use Lui\Kocmo\Data\IblockElement;

//----------------------------------------------------------------------------------------------------------------------
function sendEmailSertifikat(){

    $data = new IblockElement();
    $arFilter = Array("IBLOCK_ID"=>12, "PROPERTY_DATE"=>date('d.m.Y'), 'PROPERTY_OPLACHEN_VALUE'=>'Да');
    $data = $data->GetData($arFilter);
    PR($data);
    if(!empty($data)){
        foreach ($data as &$egift){
            if($egift['PROPERTY']['EMAIL_SENT'] != 'Да'){

                if($egift['PROPERTY']['SHTRIH_KOD']){

                    if($egift['PROPERTY']['EMAIL']){
                        SendEmailEGiftAgent($egift);
                    }

                }else{

                    $kod = GetKodSertifikatAgent();
                    CIBlockElement::SetPropertyValuesEx($egift['ID'], false, ['OPLACHEN' => 1601, 'SHTRIH_KOD' => $kod]);
                    $egift['PROPERTY']['SHTRIH_KOD']=$kod;
                    if($egift['PROPERTY']['EMAIL']){
                        SendEmailEGiftAgent($egift);
                    }
                }
            }
        }
    }

    //return "sendEmailSertifikat();";
}
function GetKodSertifikatAgent(){
    $kod = rand(1000000, 9999999);
    if($kod){
        return $kod;
    }else{
        GetKodSertifikatAgent();
    }
}
function SendEmailEGiftAgent($egift){
    $HtmlEmail = new EGift();
    $HtmlEmail = $HtmlEmail->GetHtmlEmailOb($egift);
    $arEventFields = [
        "HTML"   => $HtmlEmail,
        "EMAIL_TO"  => $egift['PROPERTY']['EMAIL'],
    ];
    $sent_email = CEvent::Send('E-GIFT', 's1', $arEventFields);
    if($sent_email){
        CIBlockElement::SetPropertyValuesEx($egift['ID'], false, ['EMAIL_SENT' => 1602]);
    }
}
//----------------------------------------------------------------------------------------------------------------------