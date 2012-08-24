<?php
declare(encoding = 'UTF-8');
/**
 * Extension autoload configuration.
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Configuration
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.aida.de AIDA Copyright
 * @link       http://www.netresearch.de
 */

return array(
    'Netresearch_Cdn' => t3lib_extMgm::extPath(
        'nr_cdn', 'src/Netresearch/Cdn.php'
    ),
    'Netresearch_Cdn_HookCssFilelinksGetFileUrl' => t3lib_extMgm::extPath(
        'nr_cdn', 'src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php'
    ),
    'tx_Netresearch_Cdn_HookCssFilelinksGetFileUrl' => t3lib_extMgm::extPath(
        'nr_cdn', 'src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php'
    ),
    'user_t3libpagerenderer' => t3lib_extMgm::extPath(
        'nr_cdn', 'class.user_t3libpagerenderer.php'
    ),
    'ux_tslib_cObj' => t3lib_extMgm::extPath(
        'nr_cdn', 'class.ux_tslib_content.php'
    ),
    'ux_ux_tslib_fe' => t3lib_extMgm::extPath(
        'nr_cdn', 'class.ux_tslib_fe.php'
    ),
);
