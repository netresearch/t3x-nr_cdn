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
        if (is_array($arConfig) && isset($arConfig['URL'])) {
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
	function USER($conf,$ext='')	{
		$content='';
		switch($ext)	{
			case 'INT':
				$substKey = $ext.'_SCRIPT.'.$GLOBALS['TSFE']->uniqueHash();
				$content.='<!--'.$substKey.'-->';
				$GLOBALS['TSFE']->config[$ext.'incScript'][$substKey] = array(
					'file' => $conf['includeLibs'],
					'conf' => $conf,
					'cObj' => serialize($this),
					'type' => 'FUNC'
				);
			break;
			default:
				$content.=$this->callUserFunction($conf['userFunc'],$conf,'');
			break;
		}
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        if (is_array($arConfig) && isset($arConfig['URL'])) {
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
		$content='';
		$filename=$this->stdWrap($conf['file'],$conf['file.']);
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
		$incFile = $GLOBALS['TSFE']->tmpl->getFileName($filename);
		if ($incFile)	{
			$fileinfo = t3lib_div::split_fileref($incFile);
			if (t3lib_div::inList('txt,html,htm',$fileinfo['fileext']))	{
				$content = $GLOBALS['TSFE']->tmpl->fileContent($incFile);
			} else {
					// default params...
				$parArray=array();
					// src is added
                if (is_array($arConfig) && isset($arConfig['URL'])) {
                    $parArray['src']='src="' . $arConfig['URL'] . '/' . $incFile.'"';
                } else {
                    $parArray['src']='src="'.$GLOBALS['TSFE']->absRefPrefix.$incFile.'"';
                }
				if (t3lib_div::inList('au,wav,mp3',$fileinfo['fileext']))	{
				}
				if (t3lib_div::inList('avi,mov,mpg,asf,wmv',$fileinfo['fileext']))	{
					$parArray['width'] = 'width="200"';
					$parArray['height'] = 'height="200"';
				}
				if (t3lib_div::inList('swf,swa,dcr',$fileinfo['fileext']))	{
					$parArray['quality'] = 'quality="high"';
				}
				if (t3lib_div::inList('class',$fileinfo['fileext']))	{
					$parArray['width'] = 'width="200"';
					$parArray['height'] = 'height="200"';
				}

					// fetching params
				$lines = explode(chr(10), $this->stdWrap($conf['params'],$conf['params.']));
				while(list(,$l)=each($lines))	{
					$parts = explode('=', $l);
					$parameter = strtolower(trim($parts[0]));
					$value = trim($parts[1]);
					if ((string)$value!='')	{
						$parArray[$parameter] = $parameter.'="'.htmlspecialchars($value).'"';
					} else {
						unset($parArray[$parameter]);
					}
				}
				if ($fileinfo['fileext']=='class')	{
					unset($parArray['src']);
					$parArray['code'] = 'code="'.htmlspecialchars($fileinfo['file']).'"';
					$parArray['codebase'] = 'codebase="'.htmlspecialchars($fileinfo['path']).'"';
					$content='<applet '.implode(' ',$parArray).'></applet>';
				} else {
					$content='<embed '.implode(' ',$parArray).'></embed>';
				}
			}
		}

		if ($conf['stdWrap.']) {
			$content=$this->stdWrap($content, $conf['stdWrap.']);
		}

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
		$GLOBALS['TSFE']->lastImageInfo=$info;
        $arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
		if (is_array($info))	{
			$info[3] = t3lib_div::png_to_gif_by_imagemagick($info[3]);
			$GLOBALS['TSFE']->imagesOnPage[]=$info[3];		// This array is used to collect the image-refs on the page...

			if (!strlen($conf['altText']) && !is_array($conf['altText.']))	{	// Backwards compatible:
				$conf['altText'] = $conf['alttext'];
				$conf['altText.'] = $conf['alttext.'];
			}
			$altParam = $this->getAltParam($conf);
            if (is_array($arConfig) && isset($arConfig['URL'])
                && strncmp('fileadmin/', $info[3], 10) === 0
            ) {
                $theValue = '<img src="'.htmlspecialchars($arConfig['URL'] . '/' . t3lib_div::rawUrlEncodeFP($info[3])).'" width="'.$info[0].'" height="'.$info[1].'"'.$this->getBorderAttr(' border="'.intval($conf['border']).'"').(($conf['params'] || is_array($conf['params.']))?' '.$this->stdwrap($conf['params'],$conf['params.']):'').($altParam).' />';
            } else {
                $theValue = '<img src="'.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($info[3])).'" width="'.$info[0].'" height="'.$info[1].'"'.$this->getBorderAttr(' border="'.intval($conf['border']).'"').(($conf['params'] || is_array($conf['params.']))?' '.$this->stdwrap($conf['params'],$conf['params.']):'').($altParam).' />';
            }
			if ($conf['linkWrap'])	{
				$theValue = $this->linkWrap($theValue,$conf['linkWrap']);
			} elseif ($conf['imageLinkWrap']) {
				$theValue = $this->imageLinkWrap($theValue,$info['origFile'],$conf['imageLinkWrap.']);
			}
			return $this->wrap($theValue,$conf['wrap']);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tslib_content.php']);
}
?>
