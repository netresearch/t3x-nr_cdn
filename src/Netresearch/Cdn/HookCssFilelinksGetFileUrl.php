<?php
declare(encoding = 'UTF-8');
/**
 * Class file.
 *
 * PHP version 5
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Hook
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @link       http://www.netresearch.de/
 */

/**
 * Hook for manipulating links to CSS files with CDN host.
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Hook
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @link       http://www.netresearch.de/
 */
class Netresearch_Cdn_HookCssFilelinksGetFileUrl
{
    /**
     * return url from file
     *
     * @param string $url    file url
     * @param array  $conf   TypoScript configuration
     * @param array  $record record with all information about the file
     *
     * @return string url
     */
    function getFileUrl($url, array $conf, array $record)
    {
        /** @var tslib_fe $TSFE */
        global $TSFE;

        $arConfig = $TSFE->tmpl->setup['config.']['nr_cdn.'];

        $initP  = '?id=' . $TSFE->id . '&type=' . $TSFE->type;
        if (@is_file($url)) {
            $urlEnc = str_replace('%2F', '/', rawurlencode($url));
            $locDataAdd = $conf['jumpurl.']['secure']
                ? $this->cObj->locDataJU($urlEnc, $conf['jumpurl.']['secure.'])
                : '';
            $retUrl = ($conf['jumpurl'])
                ? $TSFE->config['mainScript'] . $initP . '&jumpurl='
                    . rawurlencode($urlEnc)
                    . $locDataAdd . $TSFE->getMethodUrlIdToken
                : $urlEnc;// && $TSFE->config['config']['jumpurl_enable']
            if (is_array($arConfig) && isset($arConfig['URL'])) {
                $retUrl = str_replace(
                    'fileadmin/',
                    $arConfig['URL'] . '/fileadmin/',
                    $retUrl
                );
            }
            return htmlspecialchars($TSFE->absRefPrefix . $retUrl);
        };

        return '';
    }
}



/**
 * Dummy class for TYPO3.
 *
 * @category   Netresearch
 * @package    CDN
 * @subpackage Hook
 * @author     Sebastian Mendel <sebastian.mendel@netresearch.de>
 * @license    http://www.netresearch.de Netresearch
 * @link       http://www.netresearch.de
 */
class tx_Netresearch_Cdn_HookCssFilelinksGetFileUrl
    extends Netresearch_Cdn_HookCssFilelinksGetFileUrl
{

}

?>
