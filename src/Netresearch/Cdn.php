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

        if (strpos($strFileName, 'fileadmin/') === 0) {
            $strFileName = $strUrl . $strFileName;
        }

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

        $strContent = str_replace(
            '"fileadmin/',
            '"' . $strUrl . 'fileadmin/',
            $strContent
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