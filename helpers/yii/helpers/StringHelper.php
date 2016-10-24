<?php

namespace yii\helpers;

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
        }

        $input = strtr($input, $translate);
        $input = preg_replace('#\W#', '-', $input);
        $input = preg_replace('#\-\-+#', '-', $input);

        return strtolower(trim($input, '-'));
    }

    /**
     * @param integer $n
     * @param string  $singular
     * @param string  $some
     * @param string  $many
     *
     * @return string mixed
     */
    public function pluralize($n, $singular, $some, $many)
    {
        if( $n % 10 === 1 && $n % 100 !== 11 ) {
            return str_replace('{n}', $n, $singular);
        } elseif( $n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ) {
            return str_replace('{n}', $n, $some);
        } else {
            return str_replace('{n}', $n, $many);
        }
    }
}
