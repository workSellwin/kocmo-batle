<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $data = $arResult; ?>
<? if ($data['COUNT']) { ?>
    <form action="" method="post">
        <div class="basket container">
            <? if ($arItems = $data['ITEMS']) { ?>
                <? foreach ($arItems as $arItem) { ?>
                    <div class="basket__item">
                        <div class="basket__item-img">
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"> <img src="<?= $arItem['PREVIEW_PICTURE_SRC'] ?>" alt="<?= $arItem['NAME'] ?>"></a>
                        </div>
                        <div class="basket__item-details-wrap">
                            <div class="basket__item-details">
                                <div class="basket__item-title"><a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a></div>
                                <div class="basket__item-description"><?= $arItem['DESCRIPTION'] ?>
                                </div>
                                <div class="basket__item-sku-wrap">
                                    <div class="basket__item-sku">Артикул: 455-90-90</div>
                                    <div class="basket__item-color">
                                        <div class="product__colors-item">
                                <span class="product__colors-item-inner-border">
                                    <img src="assets/images/temp/product-colors/product-color-1.jpg" alt="">
                                </span>
                                        </div>
                                        светло-бежевый 102
                                    </div>
                                </div>
                            </div>
                            <div class="basket__item-counter counter">
                                <span class="counter__button counter__button--down js_counter__button"></span>
                                <input type="text" class="counter__input js_counter__input"
                                       value="<?= $arItem['QUANTITY'] ?>" data-max-count="100">
                                <span class="counter__button counter__button--up js_counter__button"></span>
                            </div>
                            <div class="basket__item-price-wrap">
                                <? if ($arItem['PRICE_OLD']) { ?>
                                    <div class="basket__item-old-price">
                                        <?= $arItem['PRICE_OLD']; ?> <span>руб</span>
                                    </div>
                                <? } ?>
                                <? if ($arItem['DISCOUNT']) { ?>
                                    <div class="product__sale-wrap">
                                        <? foreach ($arItem['DISCOUNT'] as $arDiscount) { ?>
                                            <div class="product__sale-item">
                                                <div class="products-item__label products-item__label--sale">
                                                    -<?= $arDiscount['PERCENT'] ?>%
                                                </div>
                                                <?= $arDiscount['NAME'] ?>
                                            </div>
                                        <? } ?>
                                    </div>
                                <? } ?>
                                <? if ($arItem['PRICE_NEW']) { ?>
                                    <div class="basket__item-price"> <?= $arItem['PRICE_NEW']; ?><span> руб</span></div>
                                <? } else { ?>
                                    <div class="basket__item-price"><?= round($arItem['BASE_PRICE'],2); ?> <span> руб</span>
                                    </div>
                                <? } ?>
                            </div>
                            <!-- делай аякс в этом методе MainJs.basketItemCloseInit -->
                            <div class="basket__item-close js_basket__item-close"></div>
                        </div>
                    </div>
                <? } ?>
            <? } ?>
        </div>

        <div class="basket-gift container">
            <div class="basket-gift__title">
                <span class="basket-gift__title-logo">КОСМО</span> дарит <span
                        class="basket-gift__title-colored">Вам</span> подарок!
            </div>

            <div class="basket__item">
                <div class="basket__item-img">
                    <!-- 183 x 183 -->
                    <img src="/assets/images/temp/basket-item.jpg" alt="">
                </div>

                <div class="basket__item-details-wrap">
                    <div class="basket__item-details">
                        <div class="basket__item-title">Elizavecca</div>
                        <div class="basket__item-description">Маска карбонатная, на основе чистка очистки от черных
                            точек,
                            500 мл.
                        </div>
                        <div class="basket__item-sku-wrap">
                            <div class="basket__item-sku">Артикул: 455-90-90</div>
                            <div class="basket__item-color">
                                <div class="product__colors-item">
                                <span class="product__colors-item-inner-border">
                                    <img src="/assets/images/temp/product-colors/product-color-1.jpg" alt="">
                                </span>
                                </div>
                                светло-бежевый 102
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="basket-additional container">
            <div class="basket-additional__item basket-discount">
                <div class="basket-additional__title">Скидки:</div>

                <div class="basket-discount__inner">
                    <div class="basket-discount__item">
                        <div class="basket-discount__item-title">КАРТА КЛИЕНТА КОСМО</div>
                        <div class="basket-discount__item-input">
                            <div class="form-field">
                                <input name="form-basket_card-number"
                                       value=""
                                       class="form-field__input"
                                       type="text"
                                       placeholder="Номер карты">
                            </div>
                        </div>
                        <div class="basket-discount__item-button">
                            <button class="btn basket-additional__btn">
                                <svg width="22" height="17">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-checked"></use>
                                </svg>

                                применить
                            </button>
                        </div>
                    </div>

                    <div class="basket-discount__item">
                        <div class="basket-discount__item-title">ПОДАРОЧНЫЙ СЕРТИФИКАТ</div>
                        <div class="basket-discount__item-input">
                            <div class="form-field">
                                <input name="form-basket_card-number"
                                       value=""
                                       class="form-field__input"
                                       type="text"
                                       placeholder="Номер сертификата">
                            </div>
                        </div>
                        <div class="basket-discount__item-button">
                            <button class="btn basket-additional__btn">
                                <svg width="22" height="17">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-checked"></use>
                                </svg>
                                применить
                            </button>
                        </div>
                    </div>

                    <div class="basket-discount__item">
                        <div class="basket-discount__item-title">ПРОМОКОД</div>
                        <div class="basket-discount__item-input">
                            <div class="form-field">
                                <input name="form-basket_card-number"
                                       value=""
                                       class="form-field__input"
                                       type="text"
                                       placeholder="Номер промокода">
                            </div>
                        </div>
                        <div class="basket-discount__item-button">
                            <button class="btn basket-additional__btn">
                                <svg width="22" height="17">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-checked"></use>
                                </svg>
                                применить
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="basket-additional__item basket-shipment">
                <div class="basket-additional__title">Доставка:</div>

                <div class="basket-shipment__inner">
                    <div class="basket-shipment__item-wrap basket-radio-wrap">
                        <div class="basket-shipment__item">
                            <label class="radio js_radio">
                                <input type="radio" class="js_basket-radio" name="form-basket_shipment-method"
                                       checked value="">

                                <div class="basket-shipment__item-info">
                                    <div class="basket-shipment__item-info-title">Курьерская доставка</div>
                                    <div class="basket-shipment__item-info-subtitle">Минск, в пределах МКАД</div>
                                </div>

                                <div class="basket-shipment__item-info">
                                    <div class="basket-shipment__item-info-second-title">Стоимость: 5 руб</div>
                                    <div class="basket-shipment__item-info-subtitle">При заказе от 50 руб бесплатно
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="basket-shipment__item-additional" style="display: none;">
                            <div class="form-field form-field--date">
                                <input name="form-register_date"
                                       value=""
                                       class="form-field__input"
                                       type="text"
                                       onclick="BX.calendar({node: this, field: this, bTime: false});"
                                       placeholder="Дата доставки">
                            </div>

                            <div class="form-field">
                                <select name="" class="js_custom-select">
                                    <option value="default" selected>Выберите время</option>
                                    <option value="10_20">С 20:30 до 22:30</option>
                                    <option value="20_30">С 22:30 до 23:30</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="basket-shipment__item-wrap basket-radio-wrap">
                        <div class="basket-shipment__item">
                            <label class="radio js_radio">
                                <input type="radio" class="js_basket-radio" name="form-basket_shipment-method"
                                       value="">


                                <div class="basket-shipment__item-info">
                                    <div class="basket-shipment__item-info-title">Самовывоз из магазина</div>
                                </div>

                                <div class="basket-shipment__item-info">
                                    <div class="basket-shipment__item-info-second-title--free">Бесплатно</div>
                                </div>
                            </label>
                        </div>
                        <div class="basket-shipment__item-additional" style="display: none;">
                            <div class="form-field">
                                <select name="" class="js_custom-select">
                                    <option value="default" selected>Выберите адрес магазина</option>
                                    <option value="10_20">адрес 1</option>
                                    <option value="20_30">адрес 2</option>
                                </select>
                            </div>

                            <div class="form-field form-field--date">
                                <input name="form-register_date"
                                       value=""
                                       class="form-field__input"
                                       type="text"
                                       onclick="BX.calendar({node: this, field: this, bTime: false});"
                                       placeholder="Дата доставки">
                            </div>

                            <div class="form-field">
                                <select name="" class="js_custom-select">
                                    <option value="default" selected>Выберите время</option>
                                    <option value="10_20">С 20:30 до 22:30</option>
                                    <option value="20_30">С 22:30 до 23:30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="basket-price container">
            <div class="basket-price__details">
                <div class="basket-price__item">
                    <div class="basket-price__item-title">Сумма заказа:</div>
                    <div class="basket-price__item-price">
                        <span class="basket-price__item-sum">235,90</span>
                        <span class="basket-price__item-currency"> руб</span>
                    </div>
                </div>

                <div class="basket-price__item">
                    <div class="basket-price__item-title">Ваша экономия:</div>
                    <div class="basket-price__item-price">
                        <span class="basket-price__item-sum">35,24</span>
                        <span class="basket-price__item-currency"> руб</span>
                    </div>
                </div>

                <div class="basket-price__item">
                    <div class="basket-price__item-title">Оплачено сертификатом:</div>
                    <div class="basket-price__item-price">
                        <span class="basket-price__item-sum">25,00</span>
                        <span class="basket-price__item-currency"> руб</span>
                    </div>
                </div>

                <div class="basket-price__item">
                    <div class="basket-price__item-title">Курьерская доставка:</div>
                    <div class="basket-price__item-price">
                        <span class="basket-price__item-sum">2,00</span>
                        <span class="basket-price__item-currency"> руб</span>
                    </div>
                </div>
            </div>

            <div class="basket-price__divider"></div>

            <div class="basket-price__total">
                Итого:
                <span class="basket-price__total-sum">235,90</span>
                <span class="basket-price__total-currency">руб</span>
            </div>
        </div>

        <hr>

        <div class="basket-payment container">
            <div class="basket-payment__title">Оплата:</div>

            <div class="basket-payment__inner">
                <label class="radio js_radio basket-radio-wrap basket-payment__item">
                    <input type="radio" class="js_basket-radio" name="form-basket_payment-method"
                           checked value="">

                    <div class="basket-payment__item-info">
                        <div class="basket-payment__item-info-title">Оплата при получении</div>
                        <div class="basket-payment__item-info-subtitle">Вы оплачиваете заказ курьеру при доставке
                        </div>
                    </div>
                </label>

                <label class="radio js_radio basket-radio-wrap basket-payment__item">
                    <input type="radio" class="js_basket-radio" name="form-basket_payment-method" value="">

                    <div class="basket-payment__item-info">
                        <div class="basket-payment__item-info-title">Оплата картой онлайн</div>
                        <div class="basket-payment__item-info-subtitle">Оплата через интернет магазин</div>
                    </div>
                </label>

                <label class="radio js_radio basket-radio-wrap basket-payment__item">
                    <input type="radio" class="js_basket-radio" name="form-basket_payment-method" value="">

                    <div class="basket-payment__item-info">
                        <div class="basket-payment__item-info-title">Оплата через ЕРИП</div>
                        <div class="basket-payment__item-info-subtitle">Оплата через сторонний сервис ЕРИП</div>
                    </div>
                </label>
            </div>

            <div class="basket-payment__logo">
                <div class="basket-payment__logo-item">
                    <img src="/assets/images/basket-payment/Visa.png" alt="">
                </div>

                <div class="basket-payment__logo-item">
                    <img src="/assets/images/basket-payment/mc.png" alt="">
                </div>

                <div class="basket-payment__logo-item">
                    <img src="/assets/images/basket-payment/webpay.png" alt="">
                </div>

                <div class="basket-payment__logo-item">
                    <img src="/assets/images/basket-payment/raschet.png" alt="">
                </div>

                <div class="basket-payment__logo-item">
                    <img src="/assets/images/basket-payment/blc.png" alt="">
                </div>
            </div>
        </div>

        <div class="basket-footer container">
            <div class="basket-footer__inner">
                <a href="#" class="basket-footer__back">
                    <svg width="25" height="17">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                    </svg>

                    Вернуться в каталог
                </a>

                <button type="submit" class="basket-footer__submit btn">
                    ОФормить заказ

                    <svg width="26" height="16">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right-bold"></use>
                    </svg>
                </button>
            </div>
        </div>
    </form>
<? } ?>
