<?php
declare(encoding = 'UTF-8');
/**
 * Class file.
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Helper
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

/**
 * Library/Helper class für CDN.
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Helper
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
        if (null === static::$arPaths) {
            static::$arPaths = array_merge(
                static::getPathsFromPhpConfig(),
                static::getPathsTypoScriptConfig()
            );
        }

        return static::$arPaths;
    }



    /**
     * Returns paths confgiured by TypoScript to be served from CDN.
     *
     * @return array
     */
    protected function getPathsTypoScriptConfig()
    {
        $arCfg = static::getConfig();

        $arPaths = array();

        if (empty($arCfg['paths']) || ! is_array($arCfg['paths'])) {
            return $arPaths;
        }

        foreach ($arCfg['paths'] as $arPath) {
            $arPaths[$arPath['path'] . '/'] = null;
            if (isset($arPath['ext']) && is_array($arPath['ext'])) {
                $arPaths[$arPath['path'] . '/'] = implode(',', $arPath['ext']);
            }
        }

        return $arPaths;
    }



    /**
     * Returns paths configured in PHP to be served from CDN.
     *
     * @return array
     */
    protected function getPathsFromPhpConfig()
    {
        if (empty($GLOBALS['CDN_CONF_VARS']['paths'])) {
            $arPaths = array('fileadmin/' => null);
            return $arPaths;
        }

        $arPaths = array();

        foreach ($GLOBALS['CDN_CONF_VARS']['paths'] as $strPath => $arFileExtensions) {
            $arPaths[$strPath  . '/'] = $arFileExtensions;
        }

        return $arPaths;
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
                    function (&$strExt) {
                        $strExt = preg_quote($strExt, '/');
                    }
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
     * Determines if relative pathes should also be prefixed with CDN URL.
     *
     * @return boolean
     */
    protected static function ignoreSlash()
    {
        static $bIgnoreSlash = null;

        if (null === $bIgnoreSlash) {
            if (false === empty($GLOBALS['CDN_CONF_VARS']['ignoreslash'])) {
                $bIgnoreSlash = true;
            }

            $arCfg = static::getConfig();
            if (isset($arCfg['ignoreslash'])) {
                $bIgnoreSlash = (bool) $arCfg['ignoreslash'];
            }
        }

        return $bIgnoreSlash;
    }



    /**
     * Adds the CDN path in front of the string, if it starts with a configured paths.
     *
     * @param string $strFileName Filename to be prefixed with CDN path.
     *
     * @return string The file path with added CDN URL, if file is inside configured paths.
     */
    public static function addHost($strFileName)
    {
        $strUrl = static::getCdnUrl();

        if (empty($strUrl)) {
            return $strFileName;
        }

        return preg_replace(
            static::getPathReplacements(), $strUrl . '\\1', $strFileName
        );
    }



    /**
     * Prefix URLs in content with CDN path.
     *
     * @param string $strContent content where to replace URls with CDN path.
     *
     * @return string
     */
    public static function addCdnPrefix($strContent)
    {
        $strUrl = static::getCdnUrl();

        if (empty($strUrl)) {
            return $strContent;
        }

        return preg_replace(
            static::getContentReplacements(), '"' . $strUrl . '\\1', $strContent
        );
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

        $strUrl = static::getCdnUrl();

        if (empty($strUrl)) {
            return false;
        }

        $restore = $GLOBALS['TSFE']->absRefPrefix;

        static::setAbsRefPrefix($strUrl);

        return $restore;
    }



    /**
     * Returns confgiured CDN URL.
     *
     * @return string
     */
    public function getCdnUrl()
    {
        return $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'];
    }



    /**
     * Returns config array.
     *
     * @return array
     */
    public function getConfig()
    {
        return $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
    }
}
