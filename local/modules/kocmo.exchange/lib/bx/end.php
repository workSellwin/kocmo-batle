<?php


namespace Kocmo\Exchange\Bx;
use Kocmo\Exchange,
    \Bitrix\Main\Loader,
    \Bitrix\Main\LoaderException;

class End
{
    protected $utils = null;
    protected $errors = [];

    public function update() : bool{

        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable('kocmo_exchange_data');
        $connection->truncateTable('kocmo_exchange_product_image');

        $this->utils = new Exchange\Utils();

        $this->utils->setModuleData('PRODUCT_LAST_UID', '');
        $this->utils->setModuleData('OFFER_LAST_UID', '');

        return true;
    }

    public function updateBrands(){

        try{
            Loader::includeModule('iblock');

            $res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => 2, "!PROPERTY_MARKA" => false, 'ACTIVE' => 'Y' ],
                false,
                false,
                ["NAME", "ID", "XML_ID", 'PROPERTY_MARKA']
            );

            $markaIds = [];

            while($fields = $res->fetch())
            {
                $markaIds[$fields['PROPERTY_MARKA_ENUM_ID']] = $fields['PROPERTY_MARKA_VALUE'];
            }
            unset($res);

            $property_enums = \CIBlockPropertyEnum::GetList(
                [],
                ["IBLOCK_ID"=>2, "CODE"=>"MARKA", 'ID' => array_keys($markaIds) ]
            );

            $brandsEnum = [];

            while($enum_fields = $property_enums->GetNext())
            {
                $brandsEnum[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
            }

            $brandsElem = [];

            $res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID"=>7 ],
                false,
                false,
                ["NAME", "ID", "XML_ID", 'PROPERTY_BRAND_BIND']
            );

            while($fields = $res->fetch() ){

                $brandsElem[$fields['PROPERTY_BRAND_BIND_VALUE']] = $fields['ID'];
            }

            $el = new \CIBlockElement;

            foreach($brandsElem as $enumXmlId => $brandId){

                if( isset($brandsEnum[$enumXmlId]) ){
                    $el->Update($brandId, ['ACTIVE' => 'Y']);
                }
                else{
                    $el->Update($brandId, ['ACTIVE' => 'N']);
                }
            }
        }
        catch (LoaderException $e){
            $this->errors[] = $e->getMessage();
        }
    }

    public function updateAvailable(){
        $bx = new Rest();
        $bx->updateAvailable();
    }

    public function activateElement(){
        $bx = new Rest();
        $bx->activateElement();
    }
}