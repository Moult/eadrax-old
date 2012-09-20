<?php
/**
 * Eadrax application/classes/kostache.php
 *
 * @package   Kostache
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Overloads i18n functionality into Kostache
 *
 * @link http://stackoverflow.com/questions/9637480/kostache-and-kohana-translation-system
 * @link https://github.com/zombor/KOstache/pull/43/files
 *
 * @package Kostache
 */
class Kostache extends Kohana_Kostache {
    /**
     * I18n callback that translates the text.
     *
     * Example of how to translate text in your template by using Kohana's I18n:
     * {{#i18n}}Email{{/i18n}}
     *
     * @return array
     */
    public function i18n()
    {
        return array('I18n', 'get');
    }
}
