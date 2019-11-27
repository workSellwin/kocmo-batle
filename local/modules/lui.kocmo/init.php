<?php

AddEventHandler("main", "OnBeforeUserRegister", ['Lui\Kocmo\Handler', 'OnBeforeUserRegister']);


AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array('Lui\Kocmo\Handler', "OnBeforeIBlockElementUpdateHandler"));