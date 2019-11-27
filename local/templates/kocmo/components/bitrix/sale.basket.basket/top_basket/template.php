<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $data = $arResult; ?>
<div class="header-basket-wrap">
    <a href="/personal/cart/"
       class="personality-state__item  personality-state__item--mobile-show">
        <? if ($data['COUNT']) { ?>
            <div class="personality-state__count"><?= $data['COUNT'] ?></div>
        <? } ?>
        <svg width="25" height="25">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
        </svg>
        Корзина
    </a>
    <? if ($arItems = $data['ITEMS']) { ?>
        <div class="header-basket">
            <div class="header-basket__inner">
                <div class="header-basket__header">
                    Товары <span>в вашей корзине</span>
                </div>
                <div class="header-basket__content-wrap">
                    <div class="header-basket__content js_header-basket__content">
                        <? foreach ($arItems as $arItem) { ?>
                            <div class="header-basket__item">
                                <div class="header-basket__item-img">
                                    <!-- 135 x 135 -->
                                    <img src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt="">
                                </div>
                                <div class="header-basket__item-details">
                                    <div class="header-basket__item-description">
                                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?= $arItem['NAME'] ?></a>
                                    </div>
                                    <div class="header-basket__item-price">
                                        <?= $arItem['FULL_PRICE'] ?><span> руб</span>
                                    </div>
                                    <div class="header-basket__item-counter-wrap">
                                        Количество:
                                        <div class="header-basket__item-counter counter">
                                            <span class="counter__button counter__button--down js_counter__button"></span>
                                            <input type="text" class="counter__input js_counter__input"
                                                   value="<?= $arItem['QUANTITY'] ?>"
                                                   data-max-count="1000"/>
                                            <span class="counter__button counter__button--up js_counter__button"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="header-basket__item-close js_header-basket__item-close"></div>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <div class="header-basket__footer">
                    <div class="header-basket__total-wrap">
                        Заказ на сумму:&nbsp;
                        <div class="header-basket__total-price"><span><?= $data['SUM'] ?></span> руб</div>
                    </div>
                    <a href="/personal/cart/" class="btn btn--transparent header-basket__lnk">перейти в корзину
                        <svg width="21" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xlink:href="#svg-pagination-right"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    <? } ?>
</div>
