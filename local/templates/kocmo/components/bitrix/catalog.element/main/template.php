<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$PROP = array_column($arResult['PROPERTIES'], 'VALUE', 'CODE');
$PRICE = new Price();

$arPriseType = $PRICE::getListTypePrice();

PR($PRICE::getListTypePrice());

PR($PRICE::getPriceProduct(4303, $arPriseType['ROZNICHNAYA']['XML_ID']));
PR($PRICE::getPriceProduct(4303, $arPriseType['AKTSIONNAYA']['XML_ID']));
PR($PRICE::getListGroupID());
PR($PRICE::savePrice( $arPriseType['ROZNICHNAYA']['ID'], 56, 4303, 'BYN'));

?>

<script type="text/javascript">
    var DATA_OFFERS =<?echo CUtil::PhpToJSObject($arResult['dataOffers'])?>;
</script>

<? global $AVERAGE_RATING;

$AVERAGE_RATING = $PROP['AVERAGE_RATING'];
?>
<div class="product">
    <div class="container product__container">
        <div class="product__sliders">
            <div class="product__sliders-inner">
                <? if (!empty($arResult['RES']['PHOTO_BIG']) && !empty($arResult['RES']['PHOTO_SMAL'])): ?>
                    <div class="swiper-container product__slider js_product__slider">
                        <div class="swiper-wrapper">
                            <? if (!empty($arResult['RES']['PHOTO_BIG'])): ?>
                                <? foreach ($arResult['RES']['PHOTO_BIG'] as $photo): ?>
                                    <div class="swiper-slide">
                                        <a href="<?= $photo['src'] ?>"
                                           rel="gallery1" class="fancybox">
                                            <!-- 545x361 -->
                                            <img src="<?= $photo['src'] ?>" alt="">
                                        </a>
                                    </div>
                                <? endforeach; ?>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="product__slider-thumbs-wrap">
                        <div class="swiper-container js_product__slider-thumbs">
                            <div class="swiper-wrapper">
                                <? foreach ($arResult['RES']['PHOTO_SMAL'] as $photo): ?>
                                    <div class="swiper-slide product__slider-thumbs-item">
                                        <!-- 82x54 -->
                                        <img src="<?= $photo['src'] ?>"
                                             alt="">
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                        <div class="product__slider-thumbs-prev js_product__slider-thumbs-prev disabled"></div>
                        <div class="product__slider-thumbs-next js_product__slider-thumbs-next"></div>
                    </div>
                <? endif; ?>
            </div>

            <? if ($arResult['SALES']['BANNER_IMG']): ?>
                <a href="<?= $arResult['SALES']['BANNER_URL'] ?>" class="product__banner bordered-inner">
                    <img src="<?= $arResult['SALES']['BANNER_IMG'] ?>" alt="">
                </a>
            <? endif; ?>
        </div>
        <!-- MainJs.productFormSubmit() -->
        <form action="" method="post" id="prod_form" class="product__content js_product__form">
            <input type="hidden" name="OFFERS_ID" value="<?= $arResult['ID'] ?>">
            <input type="hidden" name="OFFERS_QUANTITY" value="1">
            <input type="hidden" name="ADD_PROD_BASKET" value="Y">

            <h1 class="product__title">
                <?= $arResult['NAME'] ?>
            </h1>

            <div class="product__content-row">
                <div class="product__view">
                    <div class="product__view-inner-wrap">

                        <? if ($PROP['ARTIKUL']): ?>
                            <div class="product__view-sku">Артикул <?= $PROP['ARTIKUL'] ?></div>
                        <? endif; ?>

                        <div class="product__view-inner">
                            <div class="product__view-reviews">

                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:iblock.vote",
                                    "product_vote",
                                    array(
                                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                        "ELEMENT_ID" => $arResult['ID'],
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
                                <span class="js_scroll-to"
                                      data-scroll-to-id="reviews"><?= $PROP['COUNT_REVIEWS'] ? $PROP['COUNT_REVIEWS'] : 0 ?> отзывов</span>
                            </div>
                            <div class="product__view-watched">
                                <span class="product__view-watched-ico"></span>
                                <div style="display: inline-block"><?= $arResult['RES']['SHOW_COUNTER'] ? $arResult['RES']['SHOW_COUNTER'] : 0 ?> </div>&nbsp;просмотров
                            </div>
                        </div>

                    </div>

                    <div class="product__view-brand">
                        <img src="<?= KOCMO_TEMPLATE_PATH ?>/images/temp/product-brand.png" alt="">
                    </div>
                </div>
            </div>

            <div class="product__content-row">
                <div class="product__view">
                    <? if ($arResult['OFFERS'] || $arResult['ITEM_PRICES'][0]['PRICE']): ?>
                        <div class="product__price-wrap">
                            <div class="product__old-price" <?= $arResult['ITEM_PRICES'][0]['DISCOUNT'] ? 'style="display: block"' : 'style="display: none"' ?>>
                                <div><?= $arResult['ITEM_PRICES'][0]['BASE_PRICE'] ?></div>
                                <span> руб</span></div>
                            <div class="product__price" <?= $arResult['ITEM_PRICES'][0]['PRICE'] ? 'style="display: block"' : 'style="display: none"' ?>>
                                <div><?= $arResult['ITEM_PRICES'][0]['PRICE'] ?></div>
                                <span> руб</span></div>
                        </div>
                    <? endif; ?>
                    <? if ($arResult['OFFERS'] || $arResult['ITEM_PRICES'][0]['DISCOUNT']): ?>
                        <div class="product__sale-wrap"
                             style="<?= $arResult['ITEM_PRICES'][0]['PERCENT'] ? 'display: block' : 'display: none' ?>">

                            <div class="product__sale-item">
                                <div class="products-item__label products-item__label--first-order">
                                    -<?= $arResult['ITEM_PRICES'][0]['PERCENT'] ?>%
                                </div>
                                Скидка на товар
                            </div>

                            <? /*
                        <div class="product__sale-item">
                            <div class="products-item__label products-item__label--sale">-10%</div>
                            Акция
                        </div>
                        <div class="product__sale-item">
                            <div class="products-item__label products-item__label--promo">-20%</div>
                            Скидка по промокоду
                        </div>*/ ?>

                        </div>
                    <? endif; ?>

                    <div class="product__wishlist-wrap">
                        <a href="#" data-id="<?= $arResult['ID']; ?>"
                           class="btn h2o_add_favor2  btn--transparent product__wishlist">
                            <svg width="25" height="25">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-heart"></use>
                            </svg>
                            В избранное
                        </a>
                        <a href="#" class="show-shops">
                            <span class="show-shops__ico"></span>
                            <span class="show-shops__txt">Наличие в магазинах</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="product__content-row">
                <div class="product__footer">

                    <? if (!empty($arResult['OFFERS'])): ?>

                        <div class="product__colors">

                            <div class="product__colors-header">
                                <div class="product__colors-title-wrap">
                                    <div class="product__colors-subtitle">цвет пудры:</div>
                                    <div class="product__colors-title js_product__colors-title"></div>
                                </div>

                                <div class="product__show-hide-colors js_product__show-hide-colors"
                                     style="visibility: hidden;"></div>
                            </div>

                            <div class="product__colors-content js_product__colors-content" style="visibility: hidden;">

                                <? foreach ($arResult['OFFERS'] as $orrers): ?>
                                    <label class="product__colors-item js_product__colors-item"
                                           data-product-color-name="<?= $orrers['NAME'] ?>"
                                           id="<?= $orrers['ID'] ?>"
                                           onclick="orrers(this)">
                                    <span class="product__colors-item-inner-border">
                                        <img src="<?= $orrers['OFFERS_IMG']['src'] ?>"
                                             alt="">
                                        <input type="radio" name="color" class="product__colors-radio">
                                    </span>
                                    </label>
                                <? endforeach; ?>
                            </div>
                        </div>

                    <? endif; ?>

                    <div class="product__buy">
                        <div class="product__counter counter" <?= $arResult['CATALOG_QUANTITY'] > 0 && !$arResult['OFFERS'] && $arResult['dataOffers'][$arResult['ID']]['ADD_BASKET'] != 'Y' ? 'style="display: flex"' : 'style="display: none"' ?>>
                            <? /*js_counter__button*/ ?>
                            <span class="counter__button counter__button--down  main_js_counter__button"
                                  onclick="clickCounterButton(this)"
                                  data-offers-id="<?= $arResult['dataOffers'][$arResult['ID']]['ID'] ?>"></span>
                            <input type="text" class="counter__input js_counter__input"
                                   data-offers-id="<?= $arResult['dataOffers'][$arResult['ID']]['ID'] ?>"
                                   name="OFFERS_QUANTITY"
                                   onkeyup="keyupCounterButton(this)" value="1"
                                   data-max-count="<?= $arResult['CATALOG_QUANTITY'] ?>"/>
                            <span class="counter__button counter__button--up main_js_counter__button"
                                  onclick="clickCounterButton(this)"
                                  data-offers-id="<?= $arResult['dataOffers'][$arResult['ID']]['ID'] ?>"></span>
                        </div>

                        <button type="submit"
                                data-basket-url="/personal/cart/"
                                class="btn btn--transparent product__submit" <?= $arResult['CATALOG_QUANTITY'] > 0 ? 'style="display: flex"' : 'style="display: none"' ?>>
                            <svg width="25" height="25">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
                            </svg>
                            <span class="btn-pho"><?= $arResult['dataOffers'][$arResult['ID']]['ADD_BASKET'] != 'N' ? 'Перейти в корзину' : 'В корзину' ?></span>
                        </button>

                        <a href="#popup-preorder"
                           class="btn product__preorder js_product__preorder fancybox" <?= $arResult['CATALOG_QUANTITY'] > 0 ? 'style="display: none"' : 'style="display: block"' ?>>
                            <svg width="34" height="26">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-empty-product"></use>
                            </svg>

                            <span class="product__preorder-inner-text">
                                    нет в наличии
                                    <span>ОСТАВИТЬ ПРЕДЗАКАЗ</span>
                                </span>
                        </a>
                    </div>


                </div>
            </div>
        </form>
    </div>
</div>

<div class="product-tabs">
    <div class="container">
        <div class="tabs-wrap js_tabs-wrap">
            <div class="tabs">
                <? $i = 1;
                foreach ($arResult['RES']['PANET_TEXT'] as $key => $tabs): ?>
                    <? if ($tabs['ACTIVE'] == 'Y'): ?>
                        <span data-id="<?= $key ?>" class="tab tab--dotted js_tab <?= $i == 1 ? 'active' : '' ?>">
                            <?= $tabs['NAME'] ?>
                        </span>
                        <?
                        $i++;
                    endif; ?>
                <? endforeach; ?>
            </div>
            <div class="panels">
                <? $j = 1;
                foreach ($arResult['RES']['PANET_TEXT'] as $key => $tabs): ?>
                    <? if ($tabs['ACTIVE'] == 'Y'): ?>
                        <div data-id="<?= $key ?>" class="panel js_panel <?= $j == 1 ? 'active' : '' ?>">
                            <div class="panel__two-columns">
                                <?= $tabs['TEXT'] ?>
                            </div>
                            <div class="panel__two-columns-more js_panel__two-columns-more">читать далее</div>
                        </div>
                        <?
                        $j++;
                    endif; ?>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>


<? //PR($PROP) ?>
<? //PR($arResult['RES']) ?>
<? //PR($arResult['OFFERS']) ?>
<? //PR($arResult['ITEM_PRICES']) ?>
<? //PR($arResult) ?>



<? $APPLICATION->IncludeComponent("bh:catalog.product.subscribe", "product_subscribe", Array(
    "DETAIL_PICTURE" => $arResult['DETAIL_PICTURE']['SRC'],
    "PREVIEW_TEXT" => $arResult['PREVIEW_TEXT'],
    "NAME" => $arResult['NAME'],
    'ARTIKUL' => $PROP['ARTIKUL'],
    "BUTTON_CLASS" => "popup-preorder__submit-btn",    // CSS класс кнопки подписки
    "BUTTON_ID" => "DETAIL_PROD",    // ID кнопки подписки
    "CACHE_TIME" => "3600",    // Время кеширования (сек.)
    "CACHE_TYPE" => "A",    // Тип кеширования
    "PRODUCT_ID" => $arResult['ID'],    // ID товара
    "COMPONENT_TEMPLATE" => "product_subscribe"
),
    false
); ?>
