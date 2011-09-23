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
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aida_common/ux/class.ux_tslib_fe.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_fe.php';
    $TYPO3_CONF_VARS['EXTCONF']['css_filelinks']['pi1_hooks']['getFileUrl']
        = 'EXT:nr_cdn/class.user_cssstyledcontent_pi1.php:user_cssstyledcontent_pi1';
}
