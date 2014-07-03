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
     * @var array configured paths to explicit ignore by the CDN rewriting
     */
    static protected $arIgnorePaths = null;

    /**
     * @var array path replacement regex patterns, for use on URLs
     */
    static protected $arPathReplacements = null;

    /**
     * @var array path regex patterns to ignore for cdn replacement
     */
    static protected $arIgnorePathReplacements = null;


    /**
     * @var array path replacement regex patterns, for use in mixed HTML content
     */
    static protected $arContentReplacements = null;

    /**
     * @var array path regex patterns which should be ignored, for use in mixed HTML content
     */
    static protected $arIgnoreContentReplacements = null;

    /**
     * The delimiter to define paths that should be ignored for cdn rewrite
     *
     * @var string
     */
    static protected $strIgnorePathDelimiter = '###';


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
     * Returns configured ignore paths to be served from CDN.
     *
     * @return array
     */
    protected function getIgnorePaths()
    {
        if (null === static::$arIgnorePaths) {
            static::$arIgnorePaths = array_merge(
                static::getIgnorePathsFromPhpConfig(),
                static::getIgnorePathsTypoScriptConfig()
            );
        }

        return static::$arIgnorePaths;
    }



    /**
     * Returns paths configured by TypoScript to be served from CDN.
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
     * Returns paths to ignore configured by TypoScript to be served from CDN.
     *
     * @return array
     */
    protected function getIgnorePathsTypoScriptConfig()
    {
        $arCfg = static::getConfig();

        $arIgnorePaths = array();

        if (empty($arCfg['ignore_paths']) || ! is_array($arCfg['ignore_paths'])) {
            return $arIgnorePaths;
        }

        foreach ($arCfg['ignore_paths'] as $arIgnorePath) {
            $arIgnorePaths[$arIgnorePath['ignore_paths'] . '/'] = null;
            if (isset($arIgnorePath['ext']) && is_array($arIgnorePath['ext'])) {
                $arPaths[$arIgnorePath['ignore_paths'] . '/'] = implode(',', $arIgnorePath['ext']);
            }
        }

        return $arIgnorePaths;
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
     * Returns paths to ignore for cdn configured in PHP to be served from CDN.
     *
     * @return array
     */
    protected function getIgnorePathsFromPhpConfig()
    {
        if (empty($GLOBALS['CDN_CONF_VARS']['ignore_paths'])) {
            return array();
        }

        $arIgnorePaths = array();

        foreach ($GLOBALS['CDN_CONF_VARS']['ignore_paths'] as $strIgnorePath => $arFileExtensions) {
            $arIgnorePaths[$strIgnorePath  . '/'] = $arFileExtensions;
        }

        return $arIgnorePaths;
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
            $strPathReg .= '(?:(?:https?\:)?\/\/' . $_SERVER['HTTP_HOST'] . ')?\\/?';
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
     * Returns array with ignore patterns file URLs.
     *
     * @return array
     */
    protected function getIgnorePathReplacements()
    {
        if (null !== static::$arIgnorePathReplacements) {
            return static::$arIgnorePathReplacements;
        }

        foreach (static::getIgnorePaths() as $strIgnorePath => $arFileExtension) {
            $strPathReg = '/^';
            $strPathReg .= '(';
            $strPathReg .= preg_quote($strIgnorePath, '/');
            $strPathReg .= '[^?]*';
            if (null !== $arFileExtension) {
                $strPathReg .= '(' . implode('|', $arFileExtension) . ')';
            }
            $strPathReg .= '$)/';
            static::$arIgnorePathReplacements[] = $strPathReg;
        }

        return static::$arIgnorePathReplacements;
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
            $strPathReg .= '(?:(?:https?\:)?\/\/' . $_SERVER['HTTP_HOST'] . ')?\\/?';
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
     * Returns array with replacement patterns for use in mixed HTML content.
     *
     * @return array
     */
    protected function getIgnoreContentReplacements()
    {
        if (null !== static::$arIgnoreContentReplacements) {
            return static::$arIgnoreContentReplacements;
        }

        foreach (static::getIgnorePaths() as $strIgnorePath => $arFileExtension) {
            $strPathReg = '/\\"';
            $strPathReg .= '(?:(?:https?\:)?\/\/' . $_SERVER['HTTP_HOST'] . ')?\\/?';
            $strPathReg .= '(';
            $strPathReg .= preg_quote($strIgnorePath, '/');
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
            static::$arIgnoreContentReplacements[] = $strPathReg;
        }

        return static::$arIgnoreContentReplacements;
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
        $strOrigFileName = $strFileName;
        if (empty($strUrl)) {
            return $strFileName;
        }

        $arIgnoreContentReplacements =  static::getIgnoreContentReplacements();
        if (count($arIgnoreContentReplacements) > 0) {
            // TYPO-1840 - Replace all Patterns which have to be ignored with ###<string>###
            $strFileName = preg_replace(
                $arIgnoreContentReplacements,
                self::$strIgnorePathDelimiter
                . '\\1'
                . self::$strIgnorePathDelimiter,
                $strFileName
            );
        }
        $bHasIgnorePatterns = $strFileName !== $strOrigFileName;

        $strFileName = preg_replace(
            static::getPathReplacements(), $strUrl . '\\1', $strFileName
        );
        // Check if any ignore path has been found
        if ( false === $bHasIgnorePatterns ) {
            return $strFileName;
        }

        // TYPO-1840 - Replace all delimiter to the original form again
        return self::removeIgnoreDelimiter($strFileName);
    }

    /**
     * The function will replace all occurrence of $strIgnorePathDelimiter with ''
     * in the given string
     *
     * @param string $strVariable the variable to replace the delimiter in
     *
     * @return string returns the string without the delimiter
     */
    protected static function removeIgnoreDelimiter($strVariable)
    {
        return preg_replace('/' . self::$strIgnorePathDelimiter . '/', '', $strVariable);
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
        $strOrigContent = $strContent;
        if (empty($strUrl)) {
            return $strContent;
        }

        $arIgnoreContentReplacements =  static::getIgnoreContentReplacements();
        if (count($arIgnoreContentReplacements) > 0) {
            // TYPO-1840 - Replace all Patterns which have to be ignored with ###<string>###
            $strContent = preg_replace(
                $arIgnoreContentReplacements,
                '"'
                .self::$strIgnorePathDelimiter
                . '\\1'
                . self::$strIgnorePathDelimiter,
                $strContent
            );
        }
        // Check if any ignore path has been found
        $bHasIgnorePatterns = $strContent !== $strOrigContent;

        $strContent = preg_replace(
            static::getContentReplacements(), '"' . $strUrl . '\\1', $strContent
        );

        if ( false === $bHasIgnorePatterns ) {
            return $strContent;
        }

        // TYPO-1840 - Replace all delimiter to the original form again
        return self::removeIgnoreDelimiter($strContent);
    }



    /**
     * Set (restore) $GLOBALS['TSFE']->absRefPrefix and returns old absRefPrefix
     * or false if not changed.
     *
     * @param string|boolean $restore absRefPrefix path to set, or false to ignore
     *
     * @return string|boolean old absRefPrefix or false if not changed
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
     * Returns configured CDN URL.
     *
     * @return string
     */
    public static function getCdnUrl()
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



    /**
     * Returns true if CDN is active.
     *
     * Checks for configured CDN URL.
     *
     * @return bool
     */
    public static function isActive()
    {
        $strUrl = static::getCdnUrl();

        if (empty($strUrl)) {
            return false;
        }

        return true;
    }
}
?>
