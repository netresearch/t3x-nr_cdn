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
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @link       http://www.netresearch.de/
 */

/**
 * Extends tslib_fe for manipulating links referring to CDN host.
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Controller
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @link       http://www.netresearch.de/
 */
class ux_tslib_fe extends tslib_fe
{
    /**
     * Processes the INTinclude-scripts
     *
     * @return void
     */
    function INTincScript()
    {
        /** @var array[] $TYPO3_CONF_VARS */
        global $TYPO3_CONF_VARS;

        // Deprecated stuff:
        // @deprecated: annotation added TYPO3 4.6
        $this->additionalHeaderData
            = is_array($this->config['INTincScript_ext']['additionalHeaderData'])
            ? $this->config['INTincScript_ext']['additionalHeaderData']
            : array();
        $this->additionalJavaScript
            = $this->config['INTincScript_ext']['additionalJavaScript'];
        $this->additionalCSS = $this->config['INTincScript_ext']['additionalCSS'];
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

        $this->INTincScript_loadJSCode();
        $this->replaceHeaderData();
        // substituteHeaderSection hook for possible manipulation
        if (is_array($TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_substituteHeaderSection'])) {
            $params = array(
                'additionalHeaderData' => &$this->additionalHeaderData,
                'content'              => &$this->content,
                'divSection'           => &$this->divSection
            );
            foreach ($TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_substituteHeaderSection'] as $hook) {
                t3lib_div::callUserFunction($hook, $params, $this);
            }
        }
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
    }



    /**
     * Prepends CDN URL to page header data links.
     *
     * @return void
     */
    function replaceHeaderData()
    {
        /** @var tslib_fe $TSFE */
        global $TSFE;

        if (empty($TSFE->tmpl->setup['config.']['nr_cdn.']['URL'])) {
            return;
        }

        foreach ($this->additionalHeaderData as $strKey => $strContent) {
            $this->additionalHeaderData[$strKey]
                = Netresearch_Cdn::addCdnPrefix($strContent);
        }
    }
}

?>
