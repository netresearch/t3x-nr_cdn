<?php
declare(encoding = 'UTF-8');
require_once(t3lib_extMgm::extPath('nr_cdn').'class.ux_tslib_content.php');
/**
 * Test class for nr_cdn.
 * Generated by PHPUnit on 2010-08-25 at 08:31:45.
 */
class UxContentTest extends Netresearch_Unittest_TestCase
{
    public function tx_dummyUserFunc()
    {

        return 'foo';
    }

    public function setUp()
    {
        $this->arCdnConfig = $GLOBALS['CDN_CONF_VARS'];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']);

        $cdn = new Netresearch_Cdn();
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPaths');
        $propStatic->setValue($cdn, null);
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPathReplacements');
        $propStatic->setValue($cdn, null);
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arContentReplacements');
        $propStatic->setValue($cdn, null);

        $GLOBALS['CDN_CONF_VARS'] = $this->arConfig;
    }



    /**
     * @covers ux_tslib_cObj::TEXT
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testTextPrefixesFileadminPathInTextContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl/';

        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'fileadmin' => null,
        );

        $uxc = new ux_tslib_cObj();
        $uxc->__TEXT = 'something like "fileadmin/moredepth" will be replaced with CDN';
        $this->assertSame(
            'something like "UnittestUrl/fileadmin/moredepth" will be replaced with CDN', $uxc->TEXT(array()),
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
    }

    /**
     * @covers ux_tslib_cObj::TEXT
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testTextMustNotPrefixNonFileadminPathInTextContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl/';

        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'fileadmin' => null,
        );

        $uxc = new ux_tslib_cObj();
        $uxc->__TEXT = '"otherpath/';
        $this->assertSame(
            '"otherpath/', $uxc->TEXT(array()),
            '"otherpath/" path must not be prefixed with "UnittestUrl/"'
        );
    }

    /**
     * @covers ux_tslib_cObj::TEXT
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testTextMustNotAlterFileadminPathIfEmptyCdnUrl()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = '';

        $uxc = new ux_tslib_cObj();
        $uxc->__TEXT = '"fileadmin/';
        $this->assertSame(
            '"fileadmin/', $uxc->TEXT(array()),
            '"fileadmin/" path must be unchanged'
        );
    }

    /**
     * @covers ux_tslib_cObj::TEXT
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testTextMustNotAlterFileadminPathIfCdnUrlIsNotSet()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.'] = array();

        $uxc = new ux_tslib_cObj();
        $uxc->__TEXT = '"fileadmin/';
        $this->assertSame(
            '"fileadmin/', $uxc->TEXT(array()),
            '"fileadmin/" path must be unchanged'
        );
    }

    /**
     * @covers ux_tslib_cObj::USER
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testUserPrefixesFileadminPathInUserContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl/';

        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'fileadmin' => null,
        );

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = 'something like "fileadmin/moredepth" will be replaced with CDN';
        $this->assertSame(
            'something like "UnittestUrl/fileadmin/moredepth" will be replaced with CDN', $uxc->USER(array()),
            '"fileadmin/" path must be prefixed with "UnittestUrl/"'
        );
    }

    /**
     * @covers ux_tslib_cObj::USER
     * @covers Netresearch_Cdn::addCdnPrefix
     */
    public function testUserMustNotPrefixNonFileadminPathInUserContent()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'UnittestUrl/';

        $uxc = new ux_tslib_cObj();
        $uxc->__USER = '"otherpath/';
        $this->assertSame(
            '"otherpath/', $uxc->USER(array()),
            '"otherpath/" path must not be prefixed with "UnittestUrl/"'
        );
    }

    /**
     * @covers ux_tslib_cObj::USER
     * @covers Netresearch_Cdn::addCdnPrefix
     */
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

    /**
     * @covers ux_tslib_cObj::USER
     * @covers Netresearch_Cdn::addCdnPrefix
     */
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

    /**
     * @covers ux_tslib_cObj::MULTIMEDIA
     * @covers Netresearch_Cdn::setAbsRefPrefix
     */
    public function testMultimediaDoesAlterAndRestoreAbsPrefix()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'unittest_path/';
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

    /**
     * @covers ux_tslib_cObj::MULTIMEDIA
     * @covers Netresearch_Cdn::setAbsRefPrefix
     */
    public function testMultimediaMustNotAlterFileadminPathIfEmptyCdnUrl()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $uxc = new ux_tslib_cObj();
        $ret = $uxc->MULTIMEDIA(array());
        $testUrlBefore = $uxc->testUrlBefore;
        $this->assertSame(
            'UNITTEST', $testUrlBefore,
            'absRefPrefix must not be altered, cause CDN URL is empty'
        );

        $this->assertSame(
            'UNITTEST', $GLOBALS['TSFE']->absRefPrefix,
            'absRefPrefix must have same value as before calling MULTIMEDIA()'
        );
    }

    /**
     * @covers ux_tslib_cObj::cImage
     */
    public function testCImage()
    {
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'ext';
        $ucx = $this->getMock(
            'ux_tslib_cObj',
            array('getImgResource')
        );

        $ucx->cImage('', array());
        $testUrlBefore = $ucx->testUrlBefore;
        $this->assertSame(
            $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'],
            $testUrlBefore,
            'absRefPrefix must have same value as $GLOBALS[\'TSFE\']->tmpl->setup[\'config.\'][\'nr_cdn.\'][\'URL\']'
        );
        $this->assertSame(
            'UNITTEST', $GLOBALS['TSFE']->absRefPrefix,
            'absRefPrefix must have same value as before calling MULTIMEDIA()'
        );
    }

    /**
     * @covers ux_tslib_cObj::cImage
     */
    public function testCImageNoUrlForTheCDN()
    {
        // prepare test
        $GLOBALS['TSFE']->absRefPrefix = 'UNITTEST';
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'] = 'ext';

        // get mock
        $ucx = $this->getMock(
            'ux_tslib_cObj',
            array('getImgResource')
        );

        $ucx->cImage('', array());
        $testUrlBefore = $ucx->testUrlBefore;

        // test results
        $this->assertSame(
            $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL'],
            $testUrlBefore,
            'absRefPrefix must have same value as $GLOBALS[\'TSFE\']->tmpl->setup[\'config.\'][\'nr_cdn.\'][\'URL\']'
        );

        $this->assertSame(
            'UNITTEST', $GLOBALS['TSFE']->absRefPrefix,
            'absRefPrefix must have same value as before calling MULTIMEDIA()'
        );
    }

    /**
     * @covers ux_tslib_cObj::cImage
     */
    public function testCImageReturnsNullIfNoInfoArray()
    {
        $ucx = $this->getMock(
            'ux_tslib_cObj',
            array('getImgResource')
        );

        $ucx->expects($this->any())
            ->method('getImgResource')
            ->will($this->returnValue(''));

        $this->assertNull(
            $ucx->cImage('', array()),
            'cImage() must return null if called with invalid image'
        );
    }
}
?>
