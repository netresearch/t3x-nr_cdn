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
        $arJsLibs = array(
            'inFileadmin' => array('file' => 'fileadmin/bla'),
            'inTypo3temp' => array('file' => 'typo3temp/bla'),
        );
        $arJsFiles = array(
            'fileadmin/bla1' => array(),
            'typo3temp/bla1' => array(),
        );
        $arJsFooterFiles = array(
            'fileadmin/bla2' => array(),
            'typo3temp/bla2' => array(),
        );
        $arCssFiles = array(
            'fileadmin/bla3' => array(),
            'typo3temp/bla3' => array(),
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
        	$arTest['jsLibs']['inFileadmin']['file'],
            'UnittestUrl/fileadmin/bla',
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
        	$arTest['jsLibs']['inTypo3temp']['file'],
            'typo3temp/bla',
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
        	count($arTest['jsLibs']),
            2,
        	'There should be only 2 elements'
        );

        $this->assertTrue(
        	isset($arTest['jsFiles']['UnittestUrl/fileadmin/bla1']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
        	isset($arTest['jsFiles']['typo3temp/bla1']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
        	count($arTest['jsFiles']),
            2,
        	'There should be only 2 elements'
        );

        $this->assertTrue(
        	isset($arTest['jsFooterFiles']['UnittestUrl/fileadmin/bla2']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
        	isset($arTest['jsFooterFiles']['typo3temp/bla2']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
        	count($arTest['jsFooterFiles']),
            2,
        	'There should be only 2 elements'
        );

        $this->assertTrue(
        	isset($arTest['cssFiles']['UnittestUrl/fileadmin/bla3']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertTrue(
        	isset($arTest['cssFiles']['typo3temp/bla3']),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
        $this->assertSame(
        	count($arTest['cssFiles']),
            2,
        	'There should be only 2 elements'
        );
    }
}

?>