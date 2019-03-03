<?php

/**
 *
 * Twit Plugin for Kirby 3
 *
 * @version   0.0.1
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/twit
 * @license   MIT <http://opensource.org/licenses/MIT>
 */

@include_once __DIR__ . '/vendor/autoload.php';

require_once('src/index.php');

Kirby::plugin('hashandsalt/twit', [

    'siteMethods' => [
        'twit' => function ($type, $count) {
            $twitstatuses = twit($type, $count);
            return $twitstatuses;
        }
    ],

]);
