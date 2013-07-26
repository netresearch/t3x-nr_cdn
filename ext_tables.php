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

t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'NR CDN Setup');
?>
