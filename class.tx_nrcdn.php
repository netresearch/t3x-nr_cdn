<?php
class tx_nrcdn
{
	/**
	 * Main function replacing file locations
	 *
	 * @param array  $params tslib_fe params
	 * @param object $pObj The page object (mainly tslib_fe)
     *
	 * @return void
	 */
    function main(&$params, &$pObj)
    {
        /*$arConfig = $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'];
        if (is_array($arConfig) && isset($arConfig['URL'])) {
            $GLOBALS['TSFE']->content
                = str_replace(
                    '"fileadmin/',
                    '"' . $arConfig['URL'] . '/fileadmin/',
                    $GLOBALS['TSFE']->content
                );
        }*/
    }
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tx_templavoila_pi1.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nr_cdn/class.ux_tx_templavoila_pi1.php']);
}
?>