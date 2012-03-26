<?php
require_once(t3lib_extMgm::extPath('nr_cdn').'class.user_t3libpagerenderer.php');
/**
 * Test class for nr_cdn.
 * Generated by PHPUnit on 2010-08-25 at 08:31:45.
 */
class PageRendererTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl/';
    }

    public function tearDown()
    {
        unset($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']);
    }

    public function testRenderPreProcess()
    {
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'fileadmin' => null,
            'typo3conf' => array('.jpg', '.js'),
        );

        $arJsLibs = array(
            'inFileadmin' => array('file' => 'fileadmin/bla'),
            'inTypo3temp' => array('file' => 'other/bla'),
            'inTypo3confRight' => array('file' => 'typo3conf/image.jpg'),
            'inTypo3confWrong' => array('file' => 'typo3conf/ext/my_ext/ajax.php?abc=ajax.js'),
        );
        $arJsFiles = array(
            'fileadmin/bla1' => array(),
            'other/bla1' => array(),
            'typo3conf/image.jpg' => array(),
            'typo3conf/ext/my_ext/ajax.php?abc=ajax.js' => array(),
        );
        $arJsFooterFiles = array(
            'fileadmin/bla2' => array(),
            'other/bla2' => array(),
            'typo3conf/image.jpg' => array(),
            'typo3conf/ext/my_ext/ajax.php?abc=ajax.js' => array(),
        );
        $arCssFiles = array(
            'fileadmin/bla3' => array(),
            'other/bla3' => array(),
            'typo3conf/image.jpg' => array(),
            'typo3conf/ext/my_ext/ajax.php?abc=ajax.js' => array(),
        );
        $class = new user_t3libpagerenderer();
        $arTest = array(
            'jsLibs' => &$arJsLibs,
            'jsFiles' => &$arJsFiles,
            'jsFooterFiles' => &$arJsFooterFiles,
            'cssFiles' => &$arCssFiles,
        );

        $class->renderPreProcess($arTest, null);

        $this->assertSame(
            'UnittestUrl/fileadmin/bla',
            $arTest['jsLibs']['inFileadmin']['file'],
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            'other/bla',
            $arTest['jsLibs']['inTypo3temp']['file'],
            '"other/" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            'UnittestUrl/typo3conf/image.jpg',
            $arTest['jsLibs']['inTypo3confRight']['file'],
            '"typo3conf/image.jpg" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            'typo3conf/ext/my_ext/ajax.php?abc=ajax.js',
            $arTest['jsLibs']['inTypo3confWrong']['file'],
            '"typo3conf/ajax.php?abc=ajax.js" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            count($arTest['jsLibs']),
            4,
            'There should be only 2 elements'
        );

        $this->assertTrue(
            isset($arTest['jsFiles']['UnittestUrl/fileadmin/bla1']),
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFiles']['other/bla1']),
            '"other/" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFiles']['UnittestUrl/typo3conf/image.jpg']),
            '"typo3conf/image.jpg" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFiles']['typo3conf/ext/my_ext/ajax.php?abc=ajax.js']),
            '"typo3conf/ajax.php?abc=ajax.js" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            count($arTest['jsFiles']),
            4,
            'There should be only 2 elements'
        );

        $this->assertTrue(
            isset($arTest['jsFooterFiles']['UnittestUrl/fileadmin/bla2']),
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFooterFiles']['other/bla2']),
            '"other/" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFooterFiles']['UnittestUrl/typo3conf/image.jpg']),
            '"typo3conf/image.jpg" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['jsFooterFiles']['typo3conf/ext/my_ext/ajax.php?abc=ajax.js']),
            '"typo3conf/ajax.php?abc=ajax.js" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            count($arTest['jsFooterFiles']),
            4,
            'There should be only 2 elements'
        );

        $this->assertTrue(
            isset($arTest['cssFiles']['UnittestUrl/fileadmin/bla3']),
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['cssFiles']['other/bla3']),
            '"other/" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['cssFiles']['UnittestUrl/typo3conf/image.jpg']),
            '"typo3conf/image.jpg" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
            isset($arTest['cssFiles']['typo3conf/ext/my_ext/ajax.php?abc=ajax.js']),
            '"typo3conf/ajax.php?abc=ajax.js" path must not be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
            count($arTest['cssFiles']),
            4,
            'There should be only 2 elements'
        );
    }
}

?>