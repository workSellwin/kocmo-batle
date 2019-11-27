<?php

namespace Lui\Kocmo\Data;

class Product
{
    protected $arData = [];
    protected $id;
    protected $type;

    public function __construct($id)
    {
        if (is_int($id)) {
            $this->arData = $this->GetID($id);
            $this->type='int';
        } else {
            $this->arData = $this->GetXml($id);
            $this->type='xml';
        }
    }

    protected function GetID($id)
    {
        $arFilter = Array("ID" => $id);
        return $this->GetData($arFilter);
    }

    protected function GetXml($id)
    {
        $arFilter = Array("XML_ID" => $id);
        return $this->GetData($arFilter);
    }

    protected function GetData($arFilter = [])
    {
        $arData = [];
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "*", "PROPERTY_*");
        $res = \CIBlockElement::GetList(Array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arData[$arFields['ID']] = $arFields;
            $arData[$arFields['ID']]['PROPERTY'] = $ob->GetProperties();
        }
        return $arData;
    }


}
