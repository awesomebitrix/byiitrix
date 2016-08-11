<?php
/**
 * Frontend url manager rules
 *
 * @TODO replace f**king slashes with nginx redirects
 */

return [
    '<controller:[\d\w-]+>'                     => '<controller>',
    '<controller:[\d\w-]+>/?'                   => '<controller>',
    '<controller:[\d\w-]+>/<action:[\d\w-]+>'   => '<controller>/<action>',
    '<controller:[\d\w-]+>/<action:[\d\w-]+>/?' => '<controller>/<action>',
];
