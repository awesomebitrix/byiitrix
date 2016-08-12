<?php

namespace byiitrix\helpers\yii;

use yii\helpers\BaseStringHelper;

class StringHelper extends BaseStringHelper
{
    public static function oct2string($oct)
    {
        $out = '';

        if( preg_match_all('#\d+#', $oct, $matches) ) {
            foreach( $matches[0] as $match ) {
                $out .= chr(octdec($match));
            }
        }

        return $out;
    }

    public static function translit($input)
    {
        /**
         * @var array $translate
         */
        static $translate;

        if( $translate === NULL ) {
            /**
             * @var array $lang
             */
            $lang = \IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/js_core_translit.php', 'ru', true);
            $from = [];
            $to   = [];

            if( is_array($lang) && isset($lang['TRANS_FROM'], $lang['TRANS_TO']) ) {
                $from = explode(',', $lang['TRANS_FROM']);
                $to   = explode(',', $lang['TRANS_TO']);
            }

            $translate = array_combine($from, $to);

            $translate[' '] = '-';
            $translate['_'] = '-';
            $translate['.'] = '-';
            $translate[','] = '-';
            $translate['%'] = '-';
            $translate['!'] = '-';
            $translate['?'] = '-';
            $translate['@'] = '-';
            $translate['#'] = '-';
        }

        return strtolower(trim(preg_replace('#\-\-+#', '-', strtr($input, $translate)), '-'));
    }
}
