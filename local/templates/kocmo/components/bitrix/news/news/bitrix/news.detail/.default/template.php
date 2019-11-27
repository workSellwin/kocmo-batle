<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 1041, 'height' => 360), BX_RESIZE_IMAGE_PROPORTIONAL, true);
$date = explode(' ', $arResult['DATE_ACTIVE_FROM']);
?>
<div class="main-content">
    <? if ($file['src']): ?>
        <div class="news-inner__img">
            <img src="<?= $file['src'] ?>" alt="">
        </div>
    <? endif; ?>

    <? if ($date[0]): ?>
        <div class="news-inner__date">
            <?= $date[0] ?>
        </div>
    <? endif; ?>

    <h2 class="news-inner__title"><?=$arResult['NAME']?></h2>

    <div class="news-inner__article">

        <?=$arResult['DETAIL_TEXT']?>

        <?/*<p>Это наиболее распространенная модель очень простой механикой постоянные покупатели накапливают балы
            последующего обмена их на материальные выгоды (дисконт, бесплатный товар, специальное предложение и
            т.д.)</p>

        <p>Несмотря на кажущуюся простоту данного метода, компании умудряются настолько усложнить программу, что
            сами начинают в ней путаться.</p>

        <p>Очень хороший питательный бальзам, прекрасно питает и защищает губы в мороз и ветер. Пахнет очень
            приятно пчёлами. Пользуюсь уже 1 год, всегда ношу с собой в сумочке. Буду покупать ещё, рекомендую.
            Очень хорошо питает и защищает губы в морозную и ветреную погоду. Имеет приятный натуральный аромат
            пчёл. Хватает очень надолго, сам по себе бальзам большой. Нравится, что и состав натуральный и
            формат удобный, так как обычно все бальзамы в стиках на одной химии.</p>

        <div class="news-inner__slider-wrapper">
            <div class="news-inner__slider-title-wrap">
                <div class="news-inner__slider-title">Другие фотографии магазина:</div>

                <div class="news-inner__slider-control">
                    <div class="news-inner__slider-prev js_news-inner__slider-prev">
                        <svg width="31" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-slider-left"></use>
                        </svg>
                    </div>
                    <div class="news-inner__slider-counter js_news-inner__slider-counter" style="opacity: 0">
                        <div class="news-inner__slider-counter-current js_slider-counter-current">0</div>
                        &nbsp;/&nbsp;
                        <div class="news-inner__slider-counter-all js_slider-counter-all">0</div>
                    </div>
                    <div class="news-inner__slider-next js_news-inner__slider-next">
                        <svg width="31" height="9">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                 xlink:href="#svg-slider-right"></use>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="news-inner__slider-container swiper-container js_news-inner__slider-container">
                <div class="news-inner__slider-wrap swiper-wrapper">
                    <div class="news-inner__slider-item swiper-slide">
                        <!-- 340x221 -->
                        <img src="assets/images/temp/news-inner-1.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-2.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-3.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-1.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-2.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-3.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-1.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-2.jpg" alt="">
                    </div>
                    <div class="news-inner__slider-item swiper-slide">
                        <img src="assets/images/temp/news-inner-3.jpg" alt="">
                    </div>
                </div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <p>Это наиболее распространенная модель очень простой механикой постоянные покупатели накапливают балы
            последующего обмена их на материальные выгоды (дисконт, бесплатный товар, специальное предложение и
            т.д.) Несмотря на кажущуюся простоту данного метода, компании умудряются настолько усложнить
            программу, что сами начинают в ней путаться.</p>

        <p>Очень хороший питательный бальзам, прекрасно питает и защищает губы в мороз и ветер. Пахнет очень
            приятно пчёлами. Пользуюсь уже 1 год, всегда ношу с собой в сумочке. Буду покупать ещё, рекомендую.
            Очень хорошо питает и защищает губы в морозную и ветреную погоду. Имеет приятный натуральный аромат
            пчёл. Хватает очень надолго, сам по себе бальзам большой. Нравится, что и состав натуральный и
            формат удобный, так как обычно все бальзамы в стиках на одной химии.</p>

        <div class="news-inner__video-wrapper">
            <div class="news-inner__video">
                <iframe width="662" height="368"
                        src="https://www.youtube.com/embed/rd22nQN9a74?showinfo=0&color=blue"
                        id="ytplayer"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                </iframe>
            </div>
        </div>

        <p>
            Очень хороший питательный бальзам, прекрасно питает и защищает губы в мороз и ветер. Пахнет очень
            приятно пчёлами. Пользуюсь уже 1 год, всегда ношу с собой в сумочке. Буду покупать ещё, рекомендую.
            Очень хорошо питает и защищает губы в морозную и ветреную погоду. Имеет приятный натуральный аромат
            пчёл. Хватает очень надолго, сам по себе бальзам большой. Нравится, что и состав натуральный и
            формат удобный, так как обычно все бальзамы в стиках на одной химии. Очень хороший питательный
            бальзам, прекрасно питает и защищает губы в мороз и ветер.
        </p>
        */?>
    </div>

    <div class="news-inner__control">
        <div class="news-inner__control-item">
            <?if($arResult['navElement']['PREV']):?>
                <a href="<?=$arResult['navElement']['PREV']['DETAIL_PAGE_URL']?>" id="<?=$arResult['navElement']['PREV']['ID']?>" class="news-inner__control-prev">
                    <svg width="21" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                    </svg>
                    <span>Предыдущая новость</span>
                </a>
                <div class="news-inner__control-date"><?=explode(' ', $arResult['navElement']['PREV']['DATE_ACTIVE_FROM'])[0]?></div>
                <div class="news-inner__control-item-description">
                    <?=$arResult['navElement']['PREV']['NAME']?>
                </div>
            <?endif;?>
        </div>

        <div class="news-inner__control-item">
            <?if($arResult['navElement']['NEXT']):?>
                <a href="<?=$arResult['navElement']['NEXT']['DETAIL_PAGE_URL']?>" id="<?=$arResult['navElement']['NEXT']['ID']?>" class="news-inner__control-next">
                    <span>Следующая новость</span>
                    <svg width="21" height="9">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-right"></use>
                    </svg>
                </a>
                <div class="news-inner__control-date"><?=explode(' ', $arResult['navElement']['NEXT']['DATE_ACTIVE_FROM'])[0]?></div>
                <div class="news-inner__control-item-description">
                    <?=$arResult['navElement']['NEXT']['NAME']?>
                </div>
            <?endif;?>
        </div>

    </div>
</div>