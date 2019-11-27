<?php


namespace Kocmo\Exchange;

use Bitrix\Main\Config\Option;

class Utils
{
    private $module = 'kocmo.exchange';

    public function setModuleData(string $name, string $data){
        Option::set($this->module, $name, $data);
    }

    public function getModuleData (string $name){
        return Option::get($this->module, $name);
    }

    public function checkRef($val = false)
    {
        if( empty($val) ){
            return false;
        }

        if (is_string($val) && strlen($val) === 36 && $val != '00000000-0000-0000-0000-000000000000') {
            $arr = explode('-', $val);

            if (strlen($arr[0]) === 8 && strlen($arr[1]) === 4 && strlen($arr[2]) === 4
                && strlen($arr[3]) === 4 && strlen($arr[4]) === 12) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public function getGuid( $str ){
        return str_replace(["g_", "_"], ["", "-"], $str);
    }

    public function getStrFromGuid( $guid ){
        return "g_" . str_replace("-", "_", $guid);
    }

    public function getCode($outCode)
    {

        $newStr = "";

        for ($i = 0; $i < mb_strlen($outCode); $i++) {
            $char = mb_substr($outCode, $i, 1);

            if (strpos('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', $char) !== false && $i) {
                $newStr .= '_' . $char;
            } else {
                $newStr .= $char;
            }
        }

        return \CUtil::translit($newStr, 'ru', ['change_case' => 'U']);
    }
}