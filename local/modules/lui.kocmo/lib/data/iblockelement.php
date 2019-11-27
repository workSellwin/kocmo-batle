<?php

namespace Lui\Kocmo\Data;

class IblockElement extends Base implements IblockInterface
{
    use MinData;

    /**
     * @param int $id
     * @return array
     */
    public function GetID(int $id): array
    {
        $arFilter = Array("ID" => $id);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arId
     * @return array
     */
    public function GetIDs(array $arId): array
    {
        $arFilter = ["ID" => $arId];
        return $this->GetData($arFilter);
    }

    /**
     * @param string $id
     * @return array
     */
    public function GetXmlID(string $id): array
    {
        $arFilter = Array("XML_ID" => $id);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arXmlIDs
     * @return array
     */
    public function GetXmlIDs(array $arXmlIDs): array
    {
        $arFilter = Array("XML_ID" => $arXmlIDs);
        return $this->GetData($arFilter);
    }

    /**
     * @param array $arFilter
     * @return array
     */
    public function GetData(array $arFilter): array
    {
        $arData = [];
        $arSelect = Array("ID", "IBLOCK_ID", "*", "PROPERTY_*");
        $res = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $id = $arFields['ID'];
            $arData[$id] = $this->RemoveKeyT($arFields);
            $prop = array_column($ob->GetProperties(), 'VALUE', 'CODE');
            $prop = array_diff($prop, ['']);
            $arData[$id]['PROPERTY'] = $prop;
        }
        return $arData;
    }
}
