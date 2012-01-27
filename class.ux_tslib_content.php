<?php
declare(encoding = 'UTF-8');
/**
 * Extension config script
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

/**
 * Includes this classes since it is used for parsing HTML
 */
require_once PATH_t3lib . 'class.t3lib_parsehtml.php';

/**
 * Object TypoScript library included:
 */
if (t3lib_extMgm::isLoaded('obts')) {
    require_once t3lib_extMgm::extPath('obts', '_tsobject/_tso.php');
}

class ux_tslib_cObj extends tslib_cObj
{
    /**
     * Prefix URLs in content with CDN path.
     * 
     * @param string $strContent content where to replace URls with CDN path
     * 
     * @return string
     */
    protected static function addCdnPrefix($strContent)
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
    protected static function setAbsRefPrefix($restore = false)
    {
        if (false !== $restore) {
            $GLOBALS['TSFE']->absRefPrefix = $restore;
            return false;
        }
        
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return false;
        }
        
        $restore = $GLOBALS['TSFE']->absRefPrefix;
        
        self::setAbsRefPrefix(
            $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL']
        );

        return $restore;
    }
    
    
    
    /**
     * Rendering the cObject, TEXT
     *
     * @param array $conf TypoScript properties
     * 
     * @return string Output
     * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=350&cHash=b49de28f83
     */
    function TEXT($conf)
    {
        return self::addCdnPrefix(parent::TEXT($conf));
    }


    
    /**
     * Rendering the cObject, USER and USER_INT
     *
     * @param array  $conf TypoScript properties
     * @param string $ext  If "INT" then the cObject is a "USER_INT" (non-cached), otherwise just "USER" (cached)
     * 
     * @return string Output
     * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=369&cHash=b623aca0a9
     */
    function USER($conf, $ext = '')
    {
        return self::addCdnPrefix(parent::USER($conf, $ext));
    }

    

    /**
     * Rendering the cObject, MULTIMEDIA
     *
     * @param array $conf TypoScript properties
     * 
     * @return string Output
     * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=374&cHash=efd88ab4a9
     */
    function MULTIMEDIA($conf)    
    {
        $restore = self::setAbsRefPrefix();

        $content = parent::MULTIMEDIA($conf);
        
        self::setAbsRefPrefix($restore);
        
        return $content;
    }


    
    /**
     * Returns a <img> tag with the image file defined by $file and processed according to the properties in the TypoScript array.
     * Mostly this function is a sub-function to the IMAGE function which renders the IMAGE cObject in TypoScript. This function is called by "$this->cImage($conf['file'],$conf);" from IMAGE().
     *
     * @param string $file File TypoScript resource
     * @param array  $conf TypoScript configuration properties
     * 
     * @return string <img> tag, (possibly wrapped in links and other HTML) if any image found.
     * @access private
     * @see IMAGE()
     */
    function cImage($file, $conf) 
    {
        $info = $this->getImgResource($file, $conf['file.']);
        $bNeedAbsRefPrefix = ('fileadmin/' === substr($info[3], 0, 10));

        if ($bNeedAbsRefPrefix) {
            $restore = self::setAbsRefPrefix();
        }

        $content = parent::cImage($file, $conf);

        if ($bNeedAbsRefPrefix) {
            self::setAbsRefPrefix($restore);
        }

        return $content;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php']);
}
?>
