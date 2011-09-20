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


class user_cssstyledcontent_pi1
{
    /**
     * return url from file
     *
     * @param string $url  file url
     * @param array  $conf typoscript configuration
     *
     * @return string url
     */
    function getFileUrl($url, $conf)
    {
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        $output = '';
        $initP  = '?id=' . $GLOBALS['TSFE']->id . '&type=' . $GLOBALS['TSFE']->type;
        if (@is_file($url)) {
            $urlEnc = str_replace('%2F', '/', rawurlencode($url));
            $locDataAdd = $conf['jumpurl.']['secure'] 
                ? $this->cObj->locDataJU($urlEnc, $conf['jumpurl.']['secure.']) 
                : '';
            $retUrl = ($conf['jumpurl']) 
                ? $GLOBALS['TSFE']->config['mainScript'] . $initP . '&jumpurl='
                    . rawurlencode($urlEnc)
                    . $locDataAdd . $GLOBALS['TSFE']->getMethodUrlIdToken 
                : $urlEnc;// && $GLOBALS['TSFE']->config['config']['jumpurl_enable']
            if (is_array($arConfig) && isset($arConfig['URL'])) {
                $retUrl = str_replace(
                    'fileadmin/',
                    $arConfig['URL'] . '/fileadmin/',
                    $retUrl
                );
            }
            return htmlspecialchars($GLOBALS['TSFE']->absRefPrefix . $retUrl);
        };
        
        return '';
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_cssstyledcontent_pi1.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_cssstyledcontent_pi1.php']);
}

?>