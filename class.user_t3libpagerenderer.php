<?php
declare(encoding = 'UTF-8');
/**
 * Hook class for t3lib_pagerenderer
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
     * Prepend CDN host in JavaScript and CSS URLs.
     *
     * @param array              $arParams Parameter from render function
     * @param t3lib_PageRenderer $pObj     The object itself.
     *
     * @see t3lib_PageRenderer::render()
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



    /**
     * Prepend CDN host to asset URLs in rendered content.
     *
     * @param array              $arParams Parameter from render function
     * @param t3lib_PageRenderer $pObj     The object itself.
     *
     * @see t3lib_PageRenderer::render()
     * @return void
     */
    public function renderPostProcess($arParams, $pObj)
    {
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return;
        }

        $arToProcess = array (
            'jsLibs',              //=> &$jsLibs,
            'jsFiles',             //=> &$jsFiles,
            'jsFooterFiles',       //=> &$jsFooterFiles,
            'cssFiles',            //=> &$cssFiles,
            'headerData',          //=> &$this->headerData,
            'footerData',          //=> &$this->footerData,
            'jsInline',            //=> &$jsInline,
            'cssInline',           //=> &$cssInline,
            //'xmlPrologAndDocType', //=> &$this->xmlPrologAndDocType,
            //'htmlTag',             //=> &$this->htmlTag,
            //'headTag',             //=> &$this->headTag,
            //'charSet',             //=> &$this->charSet,
            //'metaCharsetTag',      //=> &$this->metaCharsetTag,
            'shortcutTag',         //=> &$this->shortcutTag,
            //'inlineComments',      //=> &$this->inlineComments,
            //'baseUrl',             //=> &$this->baseUrl,
            //'baseUrlTag',          //=> &$this->baseUrlTag,
            'favIcon',             //=> &$this->favIcon,
            //'iconMimeType',        //=> &$this->iconMimeType,
            //'titleTag',            //=> &$this->titleTag,
            //'title',               //=> &$this->title,
            'metaTags',            //=> &$metaTags,
            'jsFooterInline',      //=> &$jsFooterInline,
            'jsFooterLibs',        //=> &$jsFooterLibs,
            'bodyContent',         //=> &$this->bodyContent,
        );

        foreach ($arToProcess as $strToProcess) {
            if (is_string($arParams[$strToProcess])) {
                $arParams[$strToProcess]
                    = Netresearch_Cdn::addCdnPrefix($arParams[$strToProcess]);
            } elseif (is_array($arParams[$strToProcess])) {
                foreach ($arParams[$strToProcess] as &$strContent) {
                    if (is_string($strContent)) {
                        $strContent = Netresearch_Cdn::addCdnPrefix($strContent);
                    }
                }
            }
        }
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.user_t3libpagerenderer.php']);
}
?>
