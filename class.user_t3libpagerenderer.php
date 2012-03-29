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
            $arParamsProcess = array();
            foreach ($arParams[$strToProcess] as $strFileName => $arConfig) {
                if ($strToProcess === 'jsLibs') {
                    $arParamsProcess[$strFileName] = $arConfig;
                    $arParamsProcess[$strFileName]['file']
                        = Netresearch_Cdn::addHost($arConfig['file']);
                } else {
                    $strFileNameNew = Netresearch_Cdn::addHost($strFileName);
                    unset($arParams[$strToProcess][$strFileName]);
                    $arParamsProcess[$strFileNameNew] = $arConfig;
                    $arParamsProcess[$strFileNameNew]['file'] = $strFileNameNew;
                }
            }
            $arParams[$strToProcess] = $arParamsProcess;
        }
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']);
}

?>