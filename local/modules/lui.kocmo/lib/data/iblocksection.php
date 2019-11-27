<?php


namespace Lui\Kocmo\Data;


class IblockSection extends Base implements IblockInterface
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
        $arFilter = Array("ID" => $arId);
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
        $rsSections = \CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter, false, ['*', 'UF_*']);
        while ($arSection = $rsSections->GetNext()) {
            $id = $arSection['ID'];
            $arSection = $this->RemoveKeyT($arSection);
            $arData[$id] = $arSection;
        }
        return $arData;
    }
}
