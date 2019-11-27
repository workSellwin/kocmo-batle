<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use \Asdrubael\Utils;

require 'exchange_1C/load.php';
define('CATALOG_ID', 2);

if( $_GET['mode'] == "get_sections" ) {
    $tree = new Utils\treeSection();
    echo '<pre>' . print_r($tree->getTree(), true) . '</pre>';
}
elseif( $_GET['mode'] == "get_all_xmlid" ){
    $tree = new Utils\treeSection();
    echo '<pre>' . print_r($tree->getAllXmlId(), true) . '</pre>';
}
elseif( $_GET['mode'] == "create_structure" ){
    $tree = new Utils\treeSection();
    $bx = new Utils\BxSection( $tree, CATALOG_ID );
    $bx->createStruct();
    //echo '<pre>' . print_r($bx->getError(), true) . '</pre>';
}
elseif( $_GET['mode'] == "add_products" ){

    echo 'start: ', date("h:i:s"), "<br>";

    $tree = new Utils\treeProduct();
    $bx = new Utils\BxProduct( $tree, CATALOG_ID );
    $bx->addProducts();
    echo 'finish: ', date("h:i:s"), "<br>";
}
elseif( $_GET['mode'] == "update_props" ){
    $tree = new Utils\treeProperty();
    $bx = new Utils\BxProperty( $tree, CATALOG_ID );
    $bx->updateProperty();
}
//elseif( $_GET['mode'] == "save_image" ){
//    echo 'start: ', date("h:i:s"), "<br>";
//    $tree = new Utils\treeImage();
//
//    $bxImage = new Utils\BxImage( $tree, CATALOG_ID );
//    $bxImage->upload();
//    echo 'finish: ', date("h:i:s"), "<br>";
//}


