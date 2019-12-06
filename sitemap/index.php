<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent("bitrix:main.map","kocmo", Array(
        "LEVEL" => "3",
        "COL_NUM" => "1",
        "SHOW_DESCRIPTION" => "Y",
        "SET_TITLE" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600"
    )
);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>

