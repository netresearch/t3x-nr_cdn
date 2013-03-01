<?php
declare(encoding = 'UTF-8');
/**
 * Extension config script
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

/**
 * Includes this classes since it is used for parsing HTML
 */
require_once PATH_t3lib . 'class.t3lib_parsehtml.php';

/**
 * Object TypoScript library included:
 */
if (t3lib_extMgm::isLoaded('obts')) {
    require_once t3lib_extMgm::extPath('obts', '_tsobject/_tso.php');
}

/**
 * Extends tslib_cObj for manipulating links refering to CDN host.
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */
class ux_tslib_cObj extends tslib_cObj
{
    /**
     * Rendering the cObject, TEXT
     *
     * @param array $conf TypoScript properties
     *
     * @return string Output
     */
    function TEXT($conf)
    {
        return Netresearch_Cdn::addCdnPrefix(parent::TEXT($conf));
    }



    /**
     * Rendering the cObject, USER and USER_INT
     *
     * @param array  $conf TypoScript properties
     * @param string $ext  If "INT" then the cObject is a "USER_INT" (non-cached),
     *                     otherwise just "USER" (cached)
     *
     * @return string Output
     */
    function USER($conf, $ext = '')
    {
        return Netresearch_Cdn::addCdnPrefix(parent::USER($conf, $ext));
    }



    /**
     * Rendering the cObject, MULTIMEDIA
     *
     * @param array $conf TypoScript properties
     *
     * @return string Output
     */
    function MULTIMEDIA($conf)
    {
        $restore = Netresearch_Cdn::setAbsRefPrefix();

        $content = parent::MULTIMEDIA($conf);

        Netresearch_Cdn::setAbsRefPrefix($restore);

        return $content;
    }



    /**
     * Returns a <img> tag with the image file defined by $file and processed
     * according to the properties in the TypoScript array.
     * Mostly this function is a sub-function to the IMAGE function which renders
     * the IMAGE cObject in TypoScript. This function is called by
     * "$this->cImage($conf['file'],$conf);" from IMAGE().
     *
     * @param string $file File TypoScript resource
     * @param array  $conf TypoScript configuration properties
     *
     * @return string <img> tag, (possibly wrapped in links and other HTML) if
     *                any image found.
     * @access private
     * @see IMAGE()
     */
    function cImage($file, $conf)
    {
        $restore = Netresearch_Cdn::setAbsRefPrefix();

        $content = parent::cImage($file, $conf);

        Netresearch_Cdn::setAbsRefPrefix($restore);

        return $content;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php']);
}
