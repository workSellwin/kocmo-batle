<?php


namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog,
    \Bitrix\Main\Type\DateTime,
    \Bitrix\Main\DB;

class Rest extends Helper
{

    protected $stores = [];
    protected $curStore = false;
    protected $products = [];
    protected $storeXmlId = false;

    function __construct()
    {
        \Bitrix\Main\Loader::includeModule('catalog');
        $this->stores = $this->getStores();
        $storeXmlId = $this->getCurStore();

        if( !empty($storeXmlId) && $this->utils->checkRef($storeXmlId) && in_array($storeXmlId, $this->stores) ){
            $this->storeXmlId = $storeXmlId;

            $treeBuilder = new \Kocmo\Exchange\Tree\Rest($storeXmlId);
            parent::__construct($treeBuilder);
        }
        else{
            $this->storeXmlId = false;
            //throw new \Exception("store not found!");
        }
    }

    private function getCurStore(){

        $curXmlId = false;

        if( isset($this->stores) && count($this->stores)){

            $last = false;

            if( !isset($_SESSION['LAST_STORE_ID']) ){

                reset($this->stores);
                $curXmlId = current($this->stores);
                $_SESSION['LAST_STORE_ID'] = key($this->stores);
            }
            else {

                foreach ($this->stores as $id => $xml) {

                    if ($last) {
                        $_SESSION['LAST_STORE_ID'] = $id;
                        $curXmlId = $xml;
                        break;
                    }
                    if ($id == $_SESSION['LAST_STORE_ID']) {
                        $last = true;
                    }
                }
            }
        }
        return $curXmlId;
    }

    public function update() : bool {

        if($this->storeXmlId === false){

            static::resetCurStore();
            $this->status = 'end';
            $this->updateAvailable();
            return false;
        }

        $arReq = $this->treeBuilder->getRequestArr();//product xml_id => store xml_id => count
        $arUid = array_keys($arReq);
        $this->products = $this->getProductsId($arUid);
        $restIds = $this->getRestIds();

        foreach ($this->products as $id => $xml_id) {

            if( isset($arReq[$xml_id]) ){

                //$arTotalAmount = [];
                $updateStoreProductIds = [];

                foreach($arReq[$xml_id] as $storeXmlId => $amount ){

                    try {
                        if ( isset($restIds[$xml_id]) && isset($restIds[$xml_id][$storeXmlId])) {

                            $restId = $restIds[$xml_id][$storeXmlId];

                            $result = Catalog\StoreProductTable::update($restId, [
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }
                        else{
                            $result = Catalog\StoreProductTable::add([
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }

                        if($result->isSuccess()) {

                            $updateStoreProductIds[] = $result->getId();
                        }
                    } catch(DB\SqlQueryException $e){
                        //уже есть
                    } catch(\Exception $e){
                        //
                    }
                }
                $this->clearOldRest($_SESSION['LAST_STORE_ID'], $updateStoreProductIds);
            }
        }
        $this->status = 'run';
        return true;
    }

    private function getStores($xml_id = false){

        $stores = [];

        try {
            $param["filter"] = ['ACTIVE' => 'Y'];

            if( !empty($xml_id) ){
                $param["filter"] = ["XML_ID" => $xml_id];
                $param["filter"]["limit"] = 1;
            }
            $stores = Catalog\StoreTable::getlist($param)->fetchAll();
            $stores = array_column($stores, "XML_ID", "ID");
        } catch (\Exception $e){
            //
        }

        return $stores;
    }

    private function getProductsId(array $arUid){

        $res = \CIblockElement::GetList([], ["XML_ID" => $arUid], false, false, ['XML_ID', 'ID']);
        $products = [];

        while( $fields = $res->fetch() ){
            $products[$fields['ID']] = $fields['XML_ID'];
        }
        return $products;
    }

    private function getRestIds(){

        $storeProducts = [];

        try {
            $iterator = Catalog\StoreProductTable::getlist([]);

            while($row = $iterator->fetch()){
                $productXmlId = $this->products[ $row['PRODUCT_ID'] ];
                $storeProducts[$productXmlId][$this->storeXmlId] = $row['ID'];
            }
        } catch (\Exception $e){
            //
        }
        return $storeProducts;
    }

    private function updateAvailable(){

        $res = Catalog\StoreProductTable::getList(['filter' => ['STORE_ID' => 17]]);//только Независимости 6
        $productAmount = [];

        while ($row = $res->fetch()) {

            if (isset($productAmount[$row['PRODUCT_ID']])) {
                $productAmount[$row['PRODUCT_ID']] += $row['AMOUNT'];
            } else {
                $productAmount[$row['PRODUCT_ID']] = $row['AMOUNT'];
            }
        }
        $obProduct = new \CCatalogProduct();

        foreach ($productAmount as $id => $quantity) {

            if($quantity < 2){
                $quantity = 0;
            }

            $obProduct->Update($id, ['QUANTITY' => $quantity]);
        }

        $res = Catalog\ProductTable::getList([
            'filter' => ["<TIMESTAMP_X" => $this->timestamp, '>QUANTITY' => 0]
        ]);

        while($row = $res->fetch()){
            $obProduct->Update($row['ID'], ['QUANTITY' => 0]);
        }
    }

    static public function resetCurStore(){
        unset($_SESSION['LAST_STORE_ID']);
    }

    private function clearOldRest($storeId, $updateStoreProductIds){

        $res = Catalog\StoreProductTable::getList([
            'filter' => ['STORE_ID' => $storeId, '!PRODUCT_ID' => $updateStoreProductIds]
        ]);

        $arOld = [];

        while($row = $res->fetch()){
            $arOld[] = $row['ID'];
        }

        if( count($arOld)){
            foreach ($arOld as $rest){
                Catalog\StoreProductTable::delete($rest);
            }
        }
    }
}