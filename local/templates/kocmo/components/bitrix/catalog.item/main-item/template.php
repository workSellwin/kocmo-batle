<?

use \Lui\Kocmo\Catalog;
$elemPrepara = new Catalog\ElementPrepara($arResult);


$arImg = $arResult['ITEM']['PREVIEW_PICTURE'] ? $arResult['ITEM']['PREVIEW_PICTURE'] : $arResult['ITEM']['DETAIL_PICTURE'] ? $arResult['ITEM']['DETAIL_PICTURE'] : [];
$file_img = [];
if ($arImg) {
    $file_img = CFile::ResizeImageGet($arImg, array('width' => 290, 'height' => 226), BX_RESIZE_IMAGE_PROPORTIONAL, true);
}


$minPriceOffer = $elemPrepara->getMinPriceOffers();
$PROP = $elemPrepara->getProp();
$countOffers = $elemPrepara->getCauntOffers();

$prop = array_column($arResult['ITEM']['PROPERTIES'], '~VALUE', 'CODE');
?>

<div class="<?= $arResult['CLASS'] ?>">
    <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>" class="products-item__img-wrap"
       style="width: 100%; height: 226px">
        <div class="products-item__labels">
            <? if ($minPriceOffer['PERCENT']): ?>
                <div class="products-item__label products-item__label--sale">-<?= $minPriceOffer['PERCENT'] ?>%</div>
            <? endif; ?>
            <? if ($PROP['NEWPRODUCT']): ?>
                <div class="products-item__label products-item__label--new">new</div>
            <? endif; ?>
            <div class="products-item__label--add" style="display: none">
                <svg width="44" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-label-add"></use>
                </svg>
            </div>
        </div>
        <!-- 290px x 226px -->
        <img src="<?= $file_img['src'] ? $file_img['src'] : KOCMO_TEMPLATE_PATH . '/images/temp/product.jpg' ?>"
             width="290" height="226" class="products-item__img"
             alt="">
    </a>
    <div class="products-item__title-wrap">
        <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>" class="products-item__title"><?= $prop['MARKA'] ?></a>
        <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>" class="products-item__options"><?= $countOffers ?>
            вариантов</a>
    </div>
    <div class="products-item__description">
        <?= $arResult['ITEM']['NAME'] ?>
    </div>
    <div class="products-item__reviews">
        <div class="products-item__stars">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:iblock.vote",
                "product_vote",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => 2,
                    "ELEMENT_ID" => $arResult['ITEM']['ID'],
                    "ELEMENT_CODE" => "",
                    "MAX_VOTE" => "5",
                    "VOTE_NAMES" => array(
                        0 => "1",
                        1 => "2",
                        2 => "3",
                        3 => "4",
                        4 => "5",
                        5 => "",
                    ),
                    "SET_STATUS_404" => "N",
                    "DISPLAY_AS_RATING" => $arParams["VOTE_DISPLAY_AS_RATING"],
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "COMPONENT_TEMPLATE" => "stars_main",
                    "MESSAGE_404" => ""
                ),
                $component,
                array(
                    "HIDE_ICONS" => "Y"
                )
            ); ?>
           <?/* <img src="/assets/images/temp/stars.png" alt="">*/?>
        </div>
        <? if ($prop['COUNT_REVIEWS']) { ?><a href="#" class="products-item__reviews-lnk"><?= $prop['COUNT_REVIEWS'] ?>
            отзыва</a><? } ?>
    </div>
    <div class="products-item__price-wrap">
        <? if ($minPriceOffer['DISCOUNT'] != 0): ?>
            <div class="products-item__price"><?= number_format($minPriceOffer['BASE_PRICE'], 2, '.', ' '); ?>
                <span> руб</span></div>
            <div class="products-item__old-price"><?= number_format($minPriceOffer['PRICE'], 2, '.', ' '); ?>
                <span> руб</span></div>
        <? else: ?>
            <div class="products-item__price"><?= number_format($minPriceOffer['PRICE'], 2, '.', ' '); ?>
                <span> руб</span></div>
        <? endif; ?>

    </div>
    <div class="products-item__btns">
        <a href="#" class="btn btn--transparent products-item__add">
            <svg width="25" height="25">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
            </svg>
            В корзину
        </a>
        <a data-id="<?=$arResult['ITEM']['ID']?>" href="#" class="btn btn--transparent h2o_add_favor products-item__wishlist">
            <svg width="25" height="25">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-heart"></use>
            </svg>
        </a>
    </div>
</div>
