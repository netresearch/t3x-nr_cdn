<?php
declare(encoding = 'UTF-8');
/**
 * Extension config script
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Config
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

/**
 *
 */
defined('TYPO3_MODE') or die('Access denied.');

if (TYPO3_MODE == 'FE') {
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['tslib/class.tslib_content.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_content.php';
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['tslib/class.tslib_fe.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_fe.php';
    $TYPO3_CONF_VARS['EXTCONF']['css_filelinks']['pi1_hooks']['getFileUrl']
        = 'tx_Netresearch_Cdn_HookCssFilelinksGetFileUrl';
    $TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][]
        = 'EXT:nr_cdn/class.user_t3libpagerenderer.php:user_t3libpagerenderer->renderPreProcess';
    $TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][]
        = 'EXT:nr_cdn/class.user_t3libpagerenderer.php:user_t3libpagerenderer->renderPostProcess';

    /*
    $GLOBALS['CDN_CONF_VARS'] = array(
        // host name for CDN like cdn.example.org
        'host' => '',

        // whether to ignore leading slahes in given relacement paths
        'ignoreslash' => true,

        // paths to be replaced/prefixed with CDN host
        'paths' => array(
            'fileadmin' => null, // every file
            'typo3temp' => null, // every file
            'typo3conf' => array('.js', '.png', '.gif', '.jpg'), // only static files
        ),
    );
    */
}
?>
