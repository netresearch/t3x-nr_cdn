<?php
declare(encoding = 'UTF-8');
/**
 * Hook Klasse für t3lib_pagerenderer
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Alexander Opitz <alexander.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */

/**
 * The class for hook calls from t3lib_pagerenderer
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Alexander Opitz <alexander.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */
class user_t3libpagerenderer
{
    /**
     * Fix urls for header JS/CSS
     *
     * @param array              $arParams Parameter from render function
     * @param t3lib_PageRenderer $pObj     The object itself.
     *
     * @return void
     */
    public function renderPreProcess($arParams, $pObj)
    {
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return;
        }

        $arToProcess = array(
            'jsLibs',
            'jsFiles',
            'jsFooterFiles',
            'cssFiles',
        );

        foreach ($arToProcess as $strToProcess) {
            foreach ($arParams[$strToProcess] as $strFileName => $arConfig) {
                $strFileNameNew = $this->addCdnHost($strFileName);

                unset($arParams[$strToProcess][$strFileName]);
                $arParamsProcess[$strFileNameNew] = $arConfig;
            }
            $arParams[$strToProcess] = $arParamsProcess;
        }
    }

    /**
     * Adds the cdn path in front of the string, if it is fileadmin.
     * 
     * @param string $strFileName Name for a file to add cdn path
     * 
     * @return string The file path with added cdn url, if file is in "fileadmin/"
     */
    private function addCdnHost($strFileName)
    {
        $strUrl = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'];
        
        $strFileName = str_replace(
            'fileadmin/',
            $strUrl . 'fileadmin/',
            $strFileName
        );
        
        return $strFileName;
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']);
}

?>