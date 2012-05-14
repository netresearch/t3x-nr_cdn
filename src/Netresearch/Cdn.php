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
    static protected $arPaths = null;

    /**
     * @var array path replacement regex patterns, for use on URLs
     */
    static protected $arPathReplacements = null;

    /**
     * @var array path replacement regex patterns, for use in mixed HTML content
     */
    static protected $arContentReplacements = null;



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
            static::$arPaths = array('fileadmin/' => null);
            return static::$arPaths;
        }

        static::$arPaths = array();

        foreach ($GLOBALS['CDN_CONF_VARS']['paths'] as $strPath => $arFileExtensions) {
            static::$arPaths[$strPath  . '/'] = $arFileExtensions;
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

        foreach (static::getPaths() as $strPath => $arFileExtension) {
            $strPathReg = '/^';
            if (static::ignoreSlash()) {
                $strPathReg .= '\\/?';
            }
            $strPathReg .= '(';
            $strPathReg .= preg_quote($strPath, '/');
            $strPathReg .= '[^?]*';
            if (null !== $arFileExtension) {
                $strPathReg .= '(' . implode('|', $arFileExtension) . ')';
            }
            $strPathReg .= '$)/';
            static::$arPathReplacements[] = $strPathReg;
        }

        return static::$arPathReplacements;
    }



    /**
     * Returns array with replacement patterns for use in mixed HTML content.
     *
     * @return array
     */
    protected function getContentReplacements()
    {
        if (null !== static::$arContentReplacements) {
            return static::$arContentReplacements;
        }

        foreach (static::getPaths() as $strPath => $arFileExtension) {
            $strPathReg = '/\\"';
            if (static::ignoreSlash()) {
                $strPathReg .= '\\/?';
            }
            $strPathReg .= '(';
            $strPathReg .= preg_quote($strPath, '/');
            $strPathReg .= '[^?"]*';
            if (null !== $arFileExtension) {
                array_walk(
                    $arFileExtension,
                    function (&$strExt) { $strExt = preg_quote($strExt, '/'); }
                );
                $strPathReg .= '(' . implode('|', $arFileExtension) . ')';
            }
            $strPathReg .= '\\")/';
            static::$arContentReplacements[] = $strPathReg;
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
        return preg_replace(
            static::getPathReplacements(), $strUrl . '\\1', $strFileName
        );
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
            static::getContentReplacements(), '"' . $strUrl . '\\1', $strContent
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