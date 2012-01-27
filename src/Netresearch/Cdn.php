<?php
declare(encoding = 'UTF-8');
/**
 * Extension config script
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Hook
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

class Netresearch_Cdn
{
    /**
     * @var array configured paths served from CDN
     */
    static $arPaths = null;

    /**
     * @var array path replacement regex patterns, for use on URLs
     */
    static $arPathReplacements = null;

    /**
     * @var array path replacement regex patterns, for use in mixed HTML content
     */
    static $arContentReplacements = null;



    /**
     * Returns configured paths to be served from CDN.
     *
     * @return array
     */
    protected function getPaths()
    {
        if (null !== static::$arPaths) {
            return static::$arPaths;
        }

        if (empty($GLOBALS['CDN_CONF_VARS']['paths'])) {
            static::$arPaths = array('fileadmin/');
            return static::$arPaths;
        }

        static::$arPaths = array();

        foreach ($GLOBALS['CDN_CONF_VARS']['paths'] as $strPath) {
            static::$arPaths[] = $strPath  . '/';
        }

        if (static::ignoreSlash()) {
            foreach ($GLOBALS['CDN_CONF_VARS']['paths'] as $strPath) {
                static::$arPaths[] = '/' . $strPath  . '/';
            }
        }

        return static::$arPaths;
    }



    /**
     * Returns array with replacement patterns file URLs.
     *
     * @return array
     */
    protected function getPathReplacements()
    {
        if (null !== static::$arPathReplacements) {
            return static::$arPathReplacements;
        }

        foreach (static::getPaths() as $strPath) {
            static::$arPathReplacements[] = '/^(' . preg_quote($strPath, '/') . ')/';
        }

        return static::$arPathReplacements;
    }



    /**
     * Returns array with replacement patterns for use in mixed HTML content.
     *
     * @return array
     */
    protected function getContentReplacments()
    {
        if (null !== static::$arContentReplacements) {
            return static::$arContentReplacements;
        }

        foreach (static::getPaths() as $strPath) {
            static::$arContentReplacements[] = '/\\"(' . preg_quote($strPath, '/') . ')/';
        }

        return static::$arContentReplacements;
    }



    /**
     * Returns whether a leading slash should be ignored when finding paths
     * to be served from CDN.
     *
     * Determines if '/fileadmin/' is prefixed exactly as 'fileadmin/' with CDN
     *
     * @return boolean
     */
    protected static function ignoreSlash()
    {
        return false === empty(
            $GLOBALS['CDN_CONF_VARS']['ignoreslash']
        );
    }



    /**
     * Adds the CDN path in front of the string, if it starts with fileadmin.
     *
     * @param string $strFileName Name for a file to add cdn path
     *
     * @return string The file path with added cdn url, if file is in "fileadmin/"
     */
    public static function addHost($strFileName)
    {
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return $strFileName;
        }

        $strUrl = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'];

        $strFileName = preg_replace(
            static::getPathReplacements(), $strUrl . '\\1', $strFileName
        );

        return $strFileName;
    }



    /**
     * Prefix URLs in content with CDN path.
     *
     * URLs are identified by '"fileadmin'.
     *
     * @param string $strContent content where to replace URls with CDN path
     *
     * @return string
     */
    public static function addCdnPrefix($strContent)
    {
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return $strContent;
        }

        $strUrl = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'];

        $strContent = preg_replace(
            static::getContentReplacments(), '"' . $strUrl . '\\1', $strContent
        );

        return $strContent;
    }



    /**
     * Set (restore) $GLOBALS['TSFE']->absRefPrefix and returns old absRefPrefix
     * or false if not changed.
     *
     * @param string $restore absRefPrefix path to set, or false to ignore
     *
     * @return string old absRefPrefix or false if not changed
     */
    public static function setAbsRefPrefix($restore = false)
    {
        if (false !== $restore) {
            $GLOBALS['TSFE']->absRefPrefix = $restore;
            return false;
        }

        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return false;
        }

        $restore = $GLOBALS['TSFE']->absRefPrefix;

        static::setAbsRefPrefix(
            $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL']
        );

        return $restore;
    }
}

?>