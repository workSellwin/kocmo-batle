<? use Lui\Kocmo\IncludeComponent as Component;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>


</div>

</main>

<footer class="footer">
    <div class="footer-top">
        <div class="container footer-top__inner">
            <a <?= URL() == '/' ? '' : 'href="/"' ?> class="footer__logo">
                <img src="/assets/images/footer-logo.png" alt="">
            </a>

            <div class="footer__contacts">
                <a href="tel:+375296665544" class="footer__contacts-phone">666-55-44</a>
                <div class="footer__contacts-schedule">
                    <span class="footer__contacts-schedule-highlighted">Горячая линия</span> <span>ежедневно с 8:00 до 22:00</span>
                </div>
            </div>
            <? Component::NewsList(['template' => 'footer_soc', 'PARENT_SECTION' => '23']) ?>
        </div>
    </div>
    <div class="footer-middle">
        <div class="container footer-middle__inner">
            <div class="footer-nav-column">
                <? Component::Menu(["NAME" => 'помощь ПОКУПАТЕЛЮ', 'template' => 'footer', 'ROOT_MENU_TYPE' => 'footer1',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(["NAME" => 'О нас', 'template' => 'footer', 'ROOT_MENU_TYPE' => 'footer2',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(["NAME" => 'Программа лояльности', 'template' => 'footer', 'ROOT_MENU_TYPE' => 'footer3',]); ?>
            </div>

            <? $APPLICATION->IncludeComponent(
                "bh:footer_subscribe",
                "",
                array(), false
            ); ?>

        </div>
    </div>
    <? Component::NewsList(['template' => 'footer_payment', 'PARENT_SECTION' => '22']) ?>
</footer>

<? Component::ShowSvg(); ?>

<div class="up-btn"></div>

</body>
</html>
