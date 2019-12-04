<?php


namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog,
    \Bitrix\Main\Type\DateTime,
    \Bitrix\Main\DB,
    \Bitrix\Main\Loader;

class Rest extends Helper
{

    protected $stores = [];
    protected $curStore = false;
    protected $products = [];
    protected $storeXmlId = false;

    function __construct()
    {
        Loader::includeModule('catalog');

        $treeBuilder = new \Kocmo\Exchange\Tree\Rest();
        parent::__construct($treeBuilder);
        $this->stores = $this->getStores();
        $storeXmlId = $this->getCurStore();

        if (!empty($storeXmlId) && $this->utils->checkRef($storeXmlId) && in_array($storeXmlId, $this->stores)) {

            $this->storeXmlId = $storeXmlId;
            $this->treeBuilder->setStoreRest($storeXmlId);
//            $treeBuilder = new \Kocmo\Exchange\Tree\Rest($storeXmlId);
//            parent::__construct($treeBuilder);
        } else {
            $this->storeXmlId = false;
            //throw new \Exception("store not found!");
        }
    }

    private function getCurStore()
    {

        $curXmlId = false;

        if (isset($this->stores) && count($this->stores)) {

            $last = false;
            $lastStoreId = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);

            if (empty($lastStoreId)) {

                reset($this->stores);
                $curXmlId = current($this->stores);
                $this->utils->setModuleData($this->arParams['LAST_STORE_ID'], key($this->stores));
            } else {

                foreach ($this->stores as $id => $xml) {

                    if ($last) {
                        $this->utils->setModuleData($this->arParams['LAST_STORE_ID'], $id);
                        break;
                    }

                    //$lastStoreId = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);

                    if ($id == $lastStoreId) {
                        $curXmlId = $xml;
                        $last = true;
                    }
                }
            }
        }
        return $curXmlId;
    }

    public function update(): bool
    {

        if ($this->storeXmlId === false) {

            $this->resetCurStore();
            $this->status = 'end';
            $this->updateAvailable();
            //$this->updateElementActivity();
            return false;
        }

        $arReq = $this->treeBuilder->getRequestArr();//product xml_id => store xml_id => count
        $arUid = array_keys($arReq);

        $this->products = $this->getProductsId($arUid);
        $restIds = $this->getRestIds();

        foreach ($this->products as $id => $xml_id) {

            if (isset($arReq[$xml_id])) {

                //$arTotalAmount = [];
                $updateStoreProductIds = [];

                foreach ($arReq[$xml_id] as $storeXmlId => $amount) {

                    try {
                        if (isset($restIds[$xml_id]) && isset($restIds[$xml_id][$storeXmlId])) {

                            $restId = $restIds[$xml_id][$storeXmlId];

                            $result = Catalog\StoreProductTable::update($restId, [
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);

                        } else {

                            $result = Catalog\StoreProductTable::add([
                                "PRODUCT_ID" => $id,
                                "AMOUNT" => $amount,
                                "STORE_ID" => array_search($storeXmlId, $this->stores)
                            ]);
                        }

                        if ($result->isSuccess()) {

                            $updateStoreProductIds[] = $result->getId();
                        }
                    } catch (DB\SqlQueryException $e) {
                        //уже есть
                    } catch (\Exception $e) {
                        //
                    }
                }
                // $lastStoreId = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);
                //$this->clearOldRest($lastStoreId, $updateStoreProductIds);//?
            }
        }
        $this->status = 'run';
        return true;
    }

    private function getStores($xml_id = false)
    {

        $stores = [];

        try {
            $param["filter"] = ['ACTIVE' => 'Y'];

            if (!empty($xml_id)) {
                $param["filter"] = ["XML_ID" => $xml_id];
                $param["filter"]["limit"] = 1;
            }
            $stores = Catalog\StoreTable::getlist($param)->fetchAll();
            $stores = array_column($stores, "XML_ID", "ID");
        } catch (\Exception $e) {
            //
        }

        return $stores;
    }

    private function getProductsId(array $arUid)
    {

        $res = \CIblockElement::GetList([], ["XML_ID" => $arUid], false, false, ['XML_ID', 'ID']);
        $products = [];

        while ($fields = $res->fetch()) {
            $products[$fields['ID']] = $fields['XML_ID'];
        }
        return $products;
    }

    private function getRestIds()
    {

        $storeProducts = [];

        try {
            $curStore = $this->utils->getModuleData($this->arParams['LAST_STORE_ID']);
            $iterator = Catalog\StoreProductTable::getlist(['STORE_ID' => $curStore]);

            while ($row = $iterator->fetch()) {
                $productXmlId = $this->products[$row['PRODUCT_ID']];
                $storeProducts[$productXmlId][$this->storeXmlId] = $row['ID'];
            }
        } catch (\Exception $e) {
            //
        }
        return $storeProducts;
    }

    public function updateAvailable()
    {

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => [2, 3]],
            false,
            false,
            ['ID', 'ACTIVE']
        );

        $ids = [];

        while ($fields = $res->fetch()) {
            $ids[$fields['ID']] = $fields['ACTIVE'];
        }

        $res = Catalog\StoreProductTable::getList([
            'filter' => [
                'STORE_ID' => [17, 32],
                //'>AMOUNT' => 1,
            ]
        ]);

        $productAmount = [];

        while ($row = $res->fetch()) {

            if (isset($productAmount[$row['PRODUCT_ID']])) {

                if($row['AMOUNT'] > 1){
                    $productAmount[$row['PRODUCT_ID']] += $row['AMOUNT'];
                }
                else{
                    $productAmount[$row['PRODUCT_ID']] += 0;
                }

            } else {
                if($row['AMOUNT'] > 1) {
                    $productAmount[$row['PRODUCT_ID']] = $row['AMOUNT'];
                }
                else{
                    $productAmount[$row['PRODUCT_ID']] += 0;
                }
            }
        }
        unset($row);

        $iterator = \Bitrix\Catalog\ProductTable::getList([
            //'filter' => ["ID" => array_keys($productAmount)]
        ]);
        $productQuantity = [];

        while($row = $iterator->fetch()){
            $productQuantity[$row['ID']] = $row['QUANTITY'];
        }

        $obProduct = new \CCatalogProduct();
        $el = new \CIBlockElement();

        foreach ($productAmount as $id => $quantity) {

            if ($quantity < 2) {
                $quantity = 0;
            }
//            else{
//
//                if($ids[$id] == 'N') {
//                    $el->Update($id, ['ACTIVE' => 'Y']);
//                }
//            }

            if($quantity != $productQuantity[$id]){
                $productQuantity[$id] = $quantity;
                $obProduct->Update($id, ['QUANTITY' => $quantity]);
            }
        }

        unset($productAmount, $id, $quantity, $row);
//pr(count($productQuantity), 14);return;
        foreach($productQuantity as $id => $quantity){

            if($quantity != 0 && $ids[$id] != 'Y'){
                $el->Update($id, ['ACTIVE' => 'Y']);
            }
            elseif($quantity == 0 && $ids[$id] != 'N'){
                $el->Update($id, ['ACTIVE' => 'N']);
            }
        }

//обязательно
//        $res = Catalog\ProductTable::getList([
//            'filter' => ["<TIMESTAMP_X" => $this->timestamp, '>QUANTITY' => 0]
//        ]);
//
//        while($row = $res->fetch()){
//            $obProduct->Update($row['ID'], ['QUANTITY' => 0]);
//            //$el->Update($row['ID'], ['ACTIVE' => 'N']);
//        }
    }

    public function resetCurStore()
    {
        $this->utils->setModuleData($this->arParams['LAST_STORE_ID'], "");
    }

//    public function updateElementActivity()
//    {
//        $res = \CIBlockElement::GetList(
//            [],
//            ["IBLOCK_ID" => 2],
//            false,
//            ["nPageSize" => 5000, "iNumPage" => 1],
//            ['ID']
//        );
//        $ids = [];
//
//        while ($fields = $res->fetch()) {
//            $ids[] = $fields['ID'];
//        }
//
//        if (!count($ids)) {
//            return false;
//        }
//        $res = Catalog\StoreProductTable::getList([
//            'filter' => [/*'!AMOUNT' => 0,*/ "PRODUCT_ID" => $ids],
//        ]);
//        $products = [];
//
//        while ($row = $res->fetch()) {
//            if (!isset($products['PRODUCT_ID'])) {
//                $products[$row['PRODUCT_ID']] = $row['AMOUNT'];
//            } else {
//                $products[$row['PRODUCT_ID']] = $products[$row['PRODUCT_ID']] + $row['AMOUNT'];
//            }
//        }
//
//        $el = new \CIBlockElement();
//
//        foreach ($products as $id => $amount) {
//
//            if ($amount < 2) {
//                $el->Update($id, ['ACTIVE' => 'N']);
//            } else {
//                $el->Update($id, ['ACTIVE' => 'Y']);
//            }
//        }
//    }

//    public function deactivateElement(){
//
//        $res = \CIBlockElement::GetList(
//            [],
//            ["IBLOCK_ID" => 2, "ACTIVE" => 'Y', "CATALOG_AVAILABLE" => 'N'],
//            false,
//            false,
//            ['ID']
//        );
//        $ids = [];
//
//        while ($fields = $res->fetch()) {
//            $ids[$fields['ID']] = true;
//        }
//
//        if (!count($ids)) {
//            return false;
//        }
//        $res = Catalog\StoreProductTable::getList([
//            'filter' => ['>AMOUNT' => 1, "PRODUCT_ID" => array_keys($ids)],
//        ]);
//
//        while ($row = $res->fetch()) {
//            $ids[ $row["PRODUCT_ID"]] = false;
//        }
//
//        $el = new \CIBlockElement();
//
//        foreach( $ids as $id => $bool){
//            if($bool){
//                $el->Update($id, ['ACTIVE' => 'N']);
//            }
//        }
//        //pr($ids, 14);
//    }

    public function activateElement(){

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => 2, "ACTIVE" => 'N', "CATALOG_AVAILABLE" => 'N'],
            false,
            false,
            ['ID']
        );
        $ids = [];

        while ($fields = $res->fetch()) {
            $ids[$fields['ID']] = 'N';
        }

        if (!count($ids)) {
            return false;
        }
        $res = Catalog\StoreProductTable::getList([
            'filter' => ['>AMOUNT' => 1, "PRODUCT_ID" => array_keys($ids)],
        ]);

        while ($row = $res->fetch()) {
            $ids[ $row["PRODUCT_ID"] ] = 'Y';
        }

        $el = new \CIBlockElement();

        foreach( $ids as $id => $active){
            if( $active == 'Y' ){
                $el->Update($id, ['ACTIVE' => 'Y']);
            }
        }
        //pr($ids, 14);
    }

    private function clearOldRest($storeId, $updateStoreProductIds)
    {

        if (!count($updateStoreProductIds)) {
            return false;
        }

        $res = Catalog\StoreProductTable::getList([
            'filter' => ['STORE_ID' => $storeId, '!PRODUCT_ID' => $updateStoreProductIds]
        ]);

        $arOld = [];

        while ($row = $res->fetch()) {
            $arOld[] = $row['ID'];
        }

        if (count($arOld)) {
            foreach ($arOld as $rest) {
                Catalog\StoreProductTable::delete($rest);
            }
        }
    }
}