<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 06.10.2019
 * Time: 13:55
 */

namespace Kocmo\Exchange\Bx;
use \Bitrix\Catalog,
    \Bitrix\Main\Application,
    \Kocmo\Exchange,
    \Bitrix\Main\DB;

class Dbproduct extends Helper
{

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

    public function update() : bool{

        $this->startTimestamp = time();
        $arForDb = $this->treeBuilder->getProductsFromReq();
        //echo '<pre>' . print_r($arForDb, true) . '</pre>';
        //die();
        $last = true;

        if( is_array($arForDb) && count($arForDb) ) {

            $last = false;

            foreach ($this->prepareFieldsGen($arForDb) as $item) {

                if($this->checkTime()){
                    return $last;
                }

                try{
//                    echo '<pre>' . print_r($item, true) . '</pre>';
//                    die();
                    if( empty($item['PARENT']) || $item['PARENT'] == $item['UID']){
                        $item['ENTRY'] = 'product';
                    }
                    else{
                        $item['ENTRY'] = 'offer';
                    }


                    $result = Exchange\DataTable::add($item);
                    if($result->isSuccess()){
                        $this->utils->setModuleData($this->arParams['PRODUCT_LAST_UID'], $item["UID"]);
                    }
                } catch ( DB\SqlQueryException $e ){
                    //например попытка добавить с не уникальным UID
                    $this->errors[] = $e->getMessage();
                }
            }
        }
        if($last === true){
            $this->utils->setModuleData($this->arParams['PRODUCT_LAST_UID'], '');
            $this->status = 'end';
        }
        return $last;
    }

    private function prepareFieldsGen(&$prodReqArr){

        foreach( $prodReqArr as $key => $item ){
            yield [
                "UID" => $key,
                "JSON" => $item["JSON"],
                "PARENT" => $item["PARENT"]
            ];
        }
    }

    static public function truncateTable(){
        $connection = Application::getConnection();
        $connection->truncateTable(Exchange\DataTable::getTableName());
    }
}