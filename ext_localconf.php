<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if(TYPO3_MODE=='FE') {
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['tslib/class.tslib_content.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_content.php';
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aida_dre_countryxs/class.ux_tslib_fe.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_fe.php';
    $TYPO3_CONF_VARS['EXTCONF']['css_filelinks']['pi1_hooks']['getFileUrl']
        = 'EXT:nr_cdn/class.user_cssstyledcontent_pi1.php:user_cssstyledcontent_pi1';
}
?>