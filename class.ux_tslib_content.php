<?php

  // Includes this classes since it is used for parsing HTML
require_once(PATH_t3lib."class.t3lib_parsehtml.php");

    // Object TypoScript library included:
if(t3lib_extMgm::isLoaded('obts')) {
    require_once(t3lib_extMgm::extPath('obts').'_tsobject/_tso.php');
}

class ux_tslib_cObj extends tslib_cObj
{
    /**
    * Rendering the cObject, TEXT
    *
    * @param	array		Array of TypoScript properties
    * @return	string		Output
    * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=350&cHash=b49de28f83
    */
    function TEXT($conf)
    {
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        $content = $this->stdWrap($conf['value'],$conf);
        if (is_array($arConfig) && ! empty($arConfig['URL'])) {
            $content = str_replace(
                '"fileadmin/',
                '"' . $arConfig['URL'] . '/fileadmin/',
                $content
            );
        }
        return $content;
    }


    /**
    * Rendering the cObject, USER and USER_INT
    *
    * @param	array		Array of TypoScript properties
    * @param	string		If "INT" then the cObject is a "USER_INT" (non-cached), otherwise just "USER" (cached)
    * @return	string		Output
    * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=369&cHash=b623aca0a9
    */
    function USER($conf,$ext='')
    {
        $content = parent::USER($conf, $ext);
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        if (is_array($arConfig) && ! empty($arConfig['URL'])) {
            $content = str_replace(
                '"fileadmin/',
                '"' . $arConfig['URL'] . '/fileadmin/',
                $content
            );
        }
        return $content;
    }


    /**
    * Rendering the cObject, MULTIMEDIA
    *
    * @param	array		Array of TypoScript properties
    * @return	string		Output
    * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=374&cHash=efd88ab4a9
    */
    function MULTIMEDIA($conf)	{
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        $tmpAbsPrefix = $GLOBALS['TSFE']->absRefPrefix;
        $conf['width']  = 200;
        $conf['height'] = 200;
        if (is_array($arConfig) && isset($arConfig['URL'])) {
            $GLOBALS['TSFE']->absRefPrefix = $arConfig['URL'] . '/';
        }

        $content = parent::MULTIMEDIA($conf);
        $GLOBALS['TSFE']->absRefPrefix = $tmpAbsPrefix;
        return $content;

    }


    /**
    * Returns a <img> tag with the image file defined by $file and processed according to the properties in the TypoScript array.
    * Mostly this function is a sub-function to the IMAGE function which renders the IMAGE cObject in TypoScript. This function is called by "$this->cImage($conf['file'],$conf);" from IMAGE().
    *
    * @param	string		File TypoScript resource
    * @param	array		TypoScript configuration properties
    * @return	string		<img> tag, (possibly wrapped in links and other HTML) if any image found.
    * @access private
    * @see IMAGE()
    */
    function cImage($file,$conf) {

        $info = $this->getImgResource($file,$conf['file.']);
        if (is_array($info)) {
            $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
            $tmpAbsPrefix = $GLOBALS['TSFE']->absRefPrefix;
            if (is_array($arConfig) && ! empty($arConfig['URL'])
                && strncmp('fileadmin/', $info[3], 10) === 0
            ) {
                $GLOBALS['TSFE']->absRefPrefix = $arConfig['URL'] . '/';
            }
            $content = parent::cImage($file, $conf);
            $GLOBALS['TSFE']->absRefPrefix = $tmpAbsPrefix;
            return $content;
        }
    }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php'])	{
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php']);
}
?>
