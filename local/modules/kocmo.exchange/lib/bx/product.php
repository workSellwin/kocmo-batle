<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog,
    \Kocmo\Exchange,
    \Bitrix\Main;

class Product extends Helper
{
    private $productMatchXmlId = [];
    protected $arProperty = [];
    protected $arEnumMatch = [];
    protected $defaultLimit = 1000;

    /**
     * Product constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        $treeBuilder = new Exchange\Tree\Product();
        parent::__construct($treeBuilder);
        unset($treeBuilder);
    }

    public function update() : bool {

        $this->startTimestamp = time();
        $oElement = new \CIBlockElement();
        $this->setMatchXmlId();
        $end = true;

        foreach ($this->productsGenerator() as $row){

            $end = false;

            if($this->checkTime()){
                return $end;
            }

            $rowId = $row['ROW_ID'];
            $detailPic = $row['DETAIL_PICTURE'];
            unset($row['DETAIL_PICTURE'], $row['ROW_ID']);

            try {
                $id = $this->addProduct($row, $oElement, $rowId);
            } catch(\Exception $e){
                $this->errors[] = $e;
            }

            if( $id > 0 && $this->utils->checkRef($detailPic)) {

                try {
                    Exchange\ProductImageTable::add(["IMG_GUI" => $detailPic, "PRODUCT_ID" => $id]);
                } catch (\Bitrix\Main\DB\SqlQueryException $e) {
                    //например попытка добавить с не уникальным IMG_GUI
                } catch (\Exception $e) {

                }
            }
        }

        if($end){
            $this->status = 'end';
            $connection = Main\Application::getConnection();
            $connection->truncateTable(Exchange\DataTable::getTableName());
        }
        else{
            $this->status = 'run';
        }
        return $end;
    }

    public function updateOne($arProduct){

        $this->setMatchXmlId();
        $sectionsMatch = $this->getAllSectionsXmlId();
        $this->setEnumMatch();

        $row = json_decode($arProduct['JSON'], true);

        $props = [];

        if (count($row[$this->arParams['PROPERTIES']])) {

            foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                $code = $this->utils->getCode($key);

                if ($this->utils->checkRef($prop) && isset($this->arEnumMatch[$prop]) ) {
                    $value = $this->arEnumMatch[$prop];
                }
                elseif(is_array($prop)) {

                    $value = [];

                    foreach($prop as $v){

                        if(isset($this->arEnumMatch[$v])){
                            $value[] = $this->arEnumMatch[$v];
                        }
                    }
                }
                else {
                    $value = $prop;
                }

                $props[$code] = $value;
            }
        }

        $arrIblockSectionId = [];

        if( is_array($row[$this->arParams['PARENT_ID']])){

            foreach($row[$this->arParams['PARENT_ID']] as $sectionXmlId){
                $arrIblockSectionId[] = $sectionsMatch[$sectionXmlId];
            }
        }

        $arFields = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $this->catalogId,
            "IBLOCK_SECTION" => $arrIblockSectionId,
            "XML_ID" => $row[$this->arParams['ID']],
            "NAME" => $row[$this->arParams['FULL_NAME']],
            "CODE" => \CUtil::translit($row[$this->arParams['NAME']], 'ru') . time(),
            "DETAIL_TEXT" => $row[$this->arParams['DESCRIPTION']],
            "PROPERTY_VALUES" => $props
        );

        if( !empty($row[$this->arParams['PIC_FILE']])){

            $objImg = new Image();
            $arPic = $objImg->getPhoto( $row[$this->arParams['PIC_FILE']] );
            $arFields["DETAIL_PICTURE"] = $arPic;
        }

        $id = 0;

        try {
            $id = $this->addProduct($arFields);
        } catch(\Exception $e){
            $this->errors[] = $e;
        }

        return $id;
    }

    public function productsGenerator(){

        if(Exchange\DataTable::getCount() == 0){
            return false;
        }

        $iterator = Exchange\DataTable::getList(['limit' => $this->defaultLimit]);

        $sectionsMatch = $this->getAllSectionsXmlId();
        $this->setEnumMatch();

        while($row = $iterator->fetch() ){

            $rowId = $row['ID'];
            $row = json_decode($row['JSON'], true);
            $props = [];

            if (count($row[$this->arParams['PROPERTIES']])) {

                foreach ($row[$this->arParams['PROPERTIES']] as $key => $prop) {

                    $code = $this->utils->getCode($key);

                    if ($this->utils->checkRef($prop) && isset($this->arEnumMatch[$prop]) ) {
                        $value = $this->arEnumMatch[$prop];
                    }
                    elseif(is_array($prop)) {

                        $value = [];

                        foreach($prop as $v){

                            if(isset($this->arEnumMatch[$v])){
                                $value[] = $this->arEnumMatch[$v];
                            }
                        }
                    }
                    else {
                        $value = $prop;
                    }

                    $props[$code] = $value;
                }
            }

            $arrIblockSectionId = [];

            if( is_array($row[$this->arParams['PARENT_ID']])){

                foreach($row[$this->arParams['PARENT_ID']] as $sectionXmlId){
                    $arrIblockSectionId[] = $sectionsMatch[$sectionXmlId];
                }
            }

            $arFields = array(
                "ROW_ID" => $rowId,
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->catalogId,
                "IBLOCK_SECTION" => $arrIblockSectionId,
                "XML_ID" => $row[$this->arParams['ID']],
                "NAME" => $row[$this->arParams['FULL_NAME']],
                "CODE" => \CUtil::translit($row[$this->arParams['NAME']], 'ru') . time(),
                "DETAIL_TEXT" => $row[$this->arParams['DESCRIPTION']],
                "DETAIL_PICTURE" => $row[$this->arParams['PIC_FILE']],
                "PROPERTY_VALUES" => $props
            );

            yield $arFields;
        }
    }

    protected function setEnumMatch(){

        $property_enums = \CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $this->catalogId]);

        while($enum_fields = $property_enums->GetNext()){
            $this->arEnumMatch[$enum_fields['XML_ID']] = $enum_fields['ID'];
        }
    }

    protected function getAllSectionsXmlId(){

        $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->catalogId);

        $iterator = $entity::getList([
            "filter" => ["IBLOCK_ID" => $this->catalogId],
            "select" => ["XML_ID", "ID"]
        ]);

        $sections = [];

        while($row = $iterator->fetch() ){
            $sections[$row['XML_ID']] = $row['ID'];
        }

        return $sections;
    }

    /**
     * @param array $arFields
     * @param bool|\CIBlockElement $oElement
     * @param bool|int|string $rowId
     * @return int
     * @throws \Exception
     */
    public function addProduct(array $arFields, $oElement = false, $rowId = false)
    {
        if($oElement == false){
            $oElement = new \CIBlockElement();
        }

        $prod = $this->getProductFromIblock($arFields["XML_ID"]);
        $id = 0;

        if ($prod === false) {

            $id = $oElement->Add($arFields);

            if(intval($id) > 0){

                if( intval($rowId) > 0){
                    $deleteResult = Exchange\DataTable::delete($rowId);
                }

                Catalog\Model\Product::add(array('fields' => ['ID' => $id]));//add to b_catalog_product
            }
            else{
                throw new \Exception("Error: ".$oElement->LAST_ERROR);
            }


        } else {
            if( $oElement->Update($prod, $arFields) ){

                $id = $prod;

                if( intval($rowId) > 0){
                    $deleteResult = Exchange\DataTable::delete($rowId);
                }
            }
        }
        return intval($id);
    }

    private function getProductFromIblock($xml_id)
    {

        if (!is_string($xml_id)) {
            return false;
        }

        if (isset($this->productMatchXmlId[$xml_id])) {
            return $this->productMatchXmlId[$xml_id];
        } else {
            return false;
        }
    }

    private function setMatchXmlId(){

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->catalogId],
            false,
            false,
            ["ID", "IBLOCK_ID", "XML_ID"]
        );

        while($fields = $res->fetch()) {
            $this->productMatchXmlId[$fields["XML_ID"]] = $fields["ID"];
        }
    }

    protected function getEnumId($xml_id, $key, $code)
    {

        $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->catalogId, "XML_ID" => $xml_id));

        if ($enum_fields = $property_enums->GetNext()) {
            return $enum_fields['ID'];
        } else {
            $value = $this->treeBuilder->getRefValue($key, $xml_id);//!
            $ibpenum = new \CIBlockPropertyEnum;

            $propId = $this->getPropIdFromCode($code);

            if (intval($propId) > 0 && !empty($value) ) {

                if ($enumId = $ibpenum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $value, "XML_ID" => $xml_id])) {
                    return $enumId;
                }
            }
        }
        return false;
    }

    protected function getEnumIdArr(array $valueArr, $code)
    {
        if (count($valueArr) == 0) {
            return false;
        }
        $returnArr = [];

        $property_enums = \CIBlockPropertyEnum::GetList([], Array("IBLOCK_ID" => $this->catalogId, "CODE" => $code));

        while ($enum_fields = $property_enums->GetNext()) {
            $returnArr[$enum_fields['ID']] = $enum_fields["VALUE"];
        }

        $valueArr = array_filter($valueArr, function($val) use ($returnArr){
            foreach($returnArr as $item){
                if($item == $val){
                    return false;
                }
            }
            return true;
        });

        if( count($valueArr) ){

            $bxPropEnum = new \CIBlockPropertyEnum;

            foreach($valueArr as $val){

                $propId = $this->getPropIdFromCode($code);

                if (intval($propId) > 0 ) {
                    if ($enumId = $bxPropEnum->Add(['PROPERTY_ID' => $propId, 'VALUE' => $val])) {
                        $returnArr[$enumId] = $val;
                    }
                }
            }
        }
        return array_keys($returnArr);
    }

    private function getPropIdFromCode($code)
    {
        $res = \CIBlockProperty::GetByID($code, $this->catalogId);

        if ($ar_res = $res->GetNext()) {
            return $ar_res['ID'];
        }
        return false;
    }
}