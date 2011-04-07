<?php

require_once PATH_site . 'typo3conf/ext/aida_dre_countryxs/class.ux_tslib_fe.php';

class ux_ux_tslib_fe extends ux_tslib_fe
{
    /**
    * Processes the INTinclude-scripts
    *
    * @return	void
    */
    function INTincScript()	{
            // Deprecated stuff:
        $this->additionalHeaderData = is_array($this->config['INTincScript_ext']['additionalHeaderData']) ? $this->config['INTincScript_ext']['additionalHeaderData'] : array();
        $this->additionalJavaScript = $this->config['INTincScript_ext']['additionalJavaScript'];
        $this->additionalCSS = $this->config['INTincScript_ext']['additionalCSS'];
        $this->JSCode = $this->additionalHeaderData['JSCode'];
        $this->JSImgCode = $this->additionalHeaderData['JSImgCode'];
        $this->divSection='';

        do {
            $INTiS_config = $this->config['INTincScript'];
            $this->INTincScript_includeLibs($INTiS_config);
            $this->INTincScript_process($INTiS_config);
                // Check if there were new items added to INTincScript during the previous execution:
            $INTiS_config = array_diff_assoc($this->config['INTincScript'], $INTiS_config);
            $reprocess = (count($INTiS_config) ? true : false);
        } while($reprocess);

        $GLOBALS['TT']->push('Substitute header section');
        $this->INTincScript_loadJSCode();
        $this->replaceHeaderData();
        $this->content = str_replace('<!--HD_'.$this->config['INTincScript_ext']['divKey'].'-->', $this->convOutputCharset(implode(chr(10),$this->additionalHeaderData),'HD'), $this->content);
        $this->content = str_replace('<!--TDS_'.$this->config['INTincScript_ext']['divKey'].'-->', $this->convOutputCharset($this->divSection,'TDS'), $this->content);
        $this->setAbsRefPrefix();
        $GLOBALS['TT']->pull();
    }

    function replaceHeaderData()
    {
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        if (is_array($arConfig) && isset($arConfig['URL'])) {
            foreach ($this->additionalHeaderData as $strKey => $strContent) {
                $this->additionalHeaderData[$strKey] = str_replace(
                    '"fileadmin/',
                    '"' . $arConfig['URL'] . '/fileadmin/',
                    $strContent
                );
            }
        }
    }

    /**
    * return url from file
    *
    * @param	string		$url: file url
    * @param	array		$conf typoscript configuration
     *
    * @return	string		url
    */
    function getFileUrl($url, $conf)
    {
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        if (is_array($arConfig) && isset($arConfig['URL'])) {
            $url = str_replace(
                '"fileadmin/',
                '"' . $arConfig['URL'] . '/fileadmin/',
                $url
            );
        }
        var_dump($url);die();
        $output = '';
        $initP = '?id='.$GLOBALS['TSFE']->id.'&type='.$GLOBALS['TSFE']->type;
        if (@is_file($url))	{
            $urlEnc = str_replace('%2F', '/', rawurlencode($url));
            $locDataAdd = $conf['jumpurl.']['secure'] ? $this->cObj->locDataJU($urlEnc,$conf['jumpurl.']['secure.']) : '';
            $retUrl = ($conf['jumpurl']) ? $GLOBALS['TSFE']->config['mainScript'].$initP.'&jumpurl='.rawurlencode($urlEnc).$locDataAdd.$GLOBALS['TSFE']->getMethodUrlIdToken : $urlEnc;		// && $GLOBALS['TSFE']->config['config']['jumpurl_enable']
            return htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.$retUrl);
        };
        return '';
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_fe.php'])	{
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_fe.php']);
}

?>