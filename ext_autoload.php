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
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

$strExtPath = t3lib_extMgm::extPath('nr_cdn');

return array(
    'netresearch_cdn'
        => $strExtPath . 'src/Netresearch/Cdn.php',
    'netresearch_cdn_hookcssfilelinksgetfileurl'
        => $strExtPath . 'src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php',
    'tx_netresearch_cdn_hookcssfilelinksgetfileurl'
        => $strExtPath . 'src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php',
    'user_t3libpagerenderer'
        => $strExtPath . 'class.user_t3libpagerenderer.php',
    'ux_tslib_cobj'
        => $strExtPath . 'class.ux_tslib_content.php',
    'ux_tslib_fe'
        => $strExtPath . 'class.ux_tslib_fe.php',
);
