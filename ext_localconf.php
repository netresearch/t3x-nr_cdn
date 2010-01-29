<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if(TYPO3_MODE=='FE') {
    $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['tslib/class.tslib_content.php']
        = t3lib_extMgm::extPath('nr_cdn') . 'class.ux_tslib_content.php';
    $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][]
        = 'EXT:nr_cdn/class.tx_nrcdn.php:&tx_nrcdn->main';
}
?>