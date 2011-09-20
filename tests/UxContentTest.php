<?php
require_once(t3lib_extMgm::extPath('nr_cdn').'class.ux_tslib_content.php');
/**
 * Test class for nr_cdn.
 * Generated by PHPUnit on 2010-08-25 at 08:31:45.
 */
class UxContentTest extends PHPUnit_Framework_TestCase
{
    public function tx_dummyUserFunc()
    {

        return 'foo';
    }



    public function tearDown()
    {
    	unset($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']);
    }



    public function testUserPrefixesFileadminPathInUserContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl';

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = '"fileadmin/moredepth';
        $this->assertSame(
        	'"UnittestUrl/fileadmin/moredepth', $uxc->USER(array()),
        	'"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
    }

    public function testUserMustNotPrefixNonFileadminPathInUserContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl';

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = '"otherpath/';
        $this->assertSame(
        	'"otherpath/', $uxc->USER(array()),
        	'"otherpath/" path must not be prefixed with "UnittestUrl/"'
        );
    }

    public function testUserMustNotAlterFileadminPathIfEmptyCdnUrl()
    {
    	$GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = '';

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = '"fileadmin/';
        $this->assertSame(
        	'"fileadmin/', $uxc->USER(array()),
        	'"fileadmin/" path must be unchanged'
        );
    }

    public function testUserMustNotAlterFileadminPathIfCdnUrlIsNotSet()
    {
    	$GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'] = array();

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = '"fileadmin/';
        $this->assertSame(
        	'"fileadmin/', $uxc->USER(array()),
        	'"fileadmin/" path must be unchanged'
        );
    }

    public function testMultimediaDoesAlterAndRestoreAbsPrefix()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'unittest_path';
        $uxc = new ux_tslib_cObj();
        $ret = $uxc->MULTIMEDIA(array());

        $this->assertSame(
        	'unittest_path/', $uxc->testUrlBefore,
        	'"$GLOBALS[\'TSFE\']->absRefPrefix" must be same as nr_cdn.URL when parent::MULTIMEDIA() is invoked.'
        );

        $this->assertSame(
        	'UNITTEST', $GLOBALS['TSFE']->absRefPrefix,
        	'"$GLOBALS[\'TSFE\']->absRefPrefix" must be restored or same as before method invocation.'
        );
    }

    public function testMultimediaMustNotAlterFileadminPathIfEmptyCdnUrl()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $uxc = new ux_tslib_cObj();
        $ret = $uxc->MULTIMEDIA(array());
        $testUrlBefore = $uxc->testUrlBefore;
        $this->assertSame('UNITTEST', $testUrlBefore);

        $this->assertSame('UNITTEST', $GLOBALS['TSFE']->absRefPrefix);
    }

    public function testCImage()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'ext';
        $ucx = $this->getMock(
            'ux_tslib_cObj',
            array('getImgResource')
        );
        $info[3] = 'fileadmin/';
        $ucx->expects($this->once())
            ->method('getImgResource')
            ->will($this->returnValue($info));

        $ucx->cImage('', array());
        $testUrlBefore = $ucx->testUrlBefore;
        $this->assertSame('ext/', $testUrlBefore);
        $this->assertSame('UNITTEST', $GLOBALS['TSFE']->absRefPrefix);
    }

    public function testCImageNoInfoArray()
    {
        $ucx = $this->getMock(
            'ux_tslib_cObj',
            array('getImgResource')
        );
        $ucx->expects($this->any())
            ->method('getImgResource')
            ->will($this->returnValue(''));
        $this->assertNull($ucx->cImage('', array()));
    }
}

class  tslib_cObj
{
    var $__USER = null;

    var $testUrlBefore = 'UNITTEST';


    function USER($conf, $ext = '')
    {
        return $this->__USER;
    }

    function MULTIMEDIA($conf, $ext = '')
    {
         $this->testUrlBefore = $GLOBALS['TSFE']->absRefPrefix;
    }

    function cImage($file='',$conf='')
    {
        $this->testUrlBefore = $GLOBALS['TSFE']->absRefPrefix;


    }
}