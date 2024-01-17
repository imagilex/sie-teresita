<?php

if(!isset($__GET_VARS_HPR_MODULE__)) {

    $__GET_VARS_HPR_MODULE__ = true;

    class Get_Vars_Helper {

        private static function varsAsDate(
            string $dia, string $mes, string $anio) {
            $fecha = "{$anio}-{$mes}-{$dia}";
            return strlen($fecha) > 2 ? $fecha : "";
        }

        static public function getPostVar(string $variable) {
            return isset($_POST[$variable]) && $_POST[$variable] != ""
                ? $_POST[$variable]
                : "";
        }

        static public function getGetVar(string $variable) {
            return isset($_GET[$variable]) && $_GET[$variable] != ""
                ? $_GET[$variable]
                : "";
        }

        static public function getPGVar(string $variable) {
            return Get_Vars_Helper::getPostVar($variable)
                . Get_Vars_Helper::getGetVar($variable);
        }

        static public function getPostDate(string $variable)
        {
            return Get_Vars_Helper::varsAsDate(
                Get_Vars_Helper::getPostVar($variable."_d"),
                Get_Vars_Helper::getPostVar($variable."_m"),
                Get_Vars_Helper::getPostVar($variable."_a"));
        }

        static public function getGetDate(string $variable)
        {
            return Get_Vars_Helper::varsAsDate(
                Get_Vars_Helper::getGetVar($variable."_d"),
                Get_Vars_Helper::getGetVar($variable."_m"),
                Get_Vars_Helper::getGetVar($variable."_a"));
        }

        static public function getPGDate(string $variable)
        {
            return Get_Vars_Helper::varsAsDate(
                Get_Vars_Helper::getPGVar($variable."_d"),
                Get_Vars_Helper::getPGVar($variable."_m"),
                Get_Vars_Helper::getPGVar($variable."_a"));
        }

    }

}
