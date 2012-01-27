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

require_once PATH_site . 'typo3conf/ext/aida_common/ux/class.ux_tslib_fe.php';

class ux_ux_tslib_fe extends ux_tslib_fe
{
    /**
     * Processes the INTinclude-scripts
     *
     * @return void
     */
    function INTincScript()    
    {
        // Deprecated stuff:
        $this->additionalHeaderData 
            = is_array($this->config['INTincScript_ext']['additionalHeaderData']) 
            ? $this->config['INTincScript_ext']['additionalHeaderData'] 
            : array();
        $this->additionalJavaScript 
            = $this->config['INTincScript_ext']['additionalJavaScript'];
        $this->additionalCSS = $this->config['INTincScript_ext']['additionalCSS'];
        $this->JSCode = $this->additionalHeaderData['JSCode'];
        $this->JSImgCode = $this->additionalHeaderData['JSImgCode'];
        $this->divSection = '';

        do {
            $INTiS_config = $this->config['INTincScript'];
            $this->INTincScript_includeLibs($INTiS_config);
            $this->INTincScript_process($INTiS_config);
            // Check if there were new items added to INTincScript during the
            // previous execution:
            $INTiS_config 
                = array_diff_assoc($this->config['INTincScript'], $INTiS_config);
            $reprocess = (count($INTiS_config) ? true : false);
        } while ($reprocess);

        $GLOBALS['TT']->push('Substitute header section');
        $this->INTincScript_loadJSCode();
        $this->replaceHeaderData();
        $this->content = str_replace(
            '<!--HD_' . $this->config['INTincScript_ext']['divKey'] . '-->', 
            $this->convOutputCharset(
                implode(chr(10), $this->additionalHeaderData), 'HD'
            ), 
            $this->content
        );
        $this->content = str_replace(
            '<!--TDS_' . $this->config['INTincScript_ext']['divKey'] . '-->', 
            $this->convOutputCharset($this->divSection, 'TDS'), 
            $this->content
        );
        $this->setAbsRefPrefix();
        $GLOBALS['TT']->pull();
    }

    
    
    /**
     * Prepends CDN URL to page header data links.
     * 
     * @return void
     */
    function replaceHeaderData()
    {
        if (empty($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return;
        }
        
        foreach ($this->additionalHeaderData as $strKey => $strContent) {
            $this->additionalHeaderData[$strKey] 
                = Netresearch_Cdn::addCdnPrefix($strContent);
        }
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_fe.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_fe.php']);
}

?>