<?php

/**
 * Test Netresearch_Cdn
 */
class Netresearch_CdnTest
    extends Netresearch_Unittest_TestCase
{
    public function setUp()
    {
        $this->arCdnConfig = $GLOBALS['CDN_CONF_VARS'];
    }

    public function tearDown()
    {
        parent::tearDown();

        $cdn = new Netresearch_Cdn();
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPaths');
        $propStatic->setValue($cdn, null);
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPathReplacements');
        $propStatic->setValue($cdn, null);
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arContentReplacements');
        $propStatic->setValue($cdn, null);

        $GLOBALS['CDN_CONF_VARS'] = $this->arCdnConfig;
    }

    public function testGetPathsNoConfig()
    {
        unset($GLOBALS['CDN_CONF_VARS']['paths']);

        $cdn = new Netresearch_Cdn();
        $method = $this->getAccessibleMethod($cdn, 'getPaths');
        $arResult = $method->invoke($cdn);

        $this->assertSame(
            array('fileadmin/' => null),
            $arResult,
            'The default config, if nothing is configured'
        );
    }

    public function testGetPathsStaticIfSet()
    {
        $cdn = new Netresearch_Cdn();
        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPaths');
        $propStatic->setValue($cdn, array('1' => '2'));
        $method = $this->getAccessibleMethod($cdn, 'getPaths');
        $arResult = $method->invoke($cdn);

        $this->assertSame(
            array('1' => '2'),
            $arResult,
            'The static var'
        );
    }

    public function testGetPathsFromConf()
    {
        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'Test1' => null,
            'Test2' => array('.abc', '.def'),
            'Test3' => null,
        );

        $cdn = new Netresearch_Cdn();
        $method = $this->getAccessibleMethod($cdn, 'getPaths');
        $arResult = $method->invoke($cdn);

        $this->assertSame(
            array (
                'Test1/' => null,
                'Test2/' => array (
                    0 => '.abc',
                    1 => '.def',
                ),
                'Test3/' => null,
            ),
            $arResult,
            'The static var'
        );
    }

    public function testGetPathReplacmentsStaticIfSet()
    {
        $cdn = new Netresearch_Cdn();

        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arPathReplacements');
        $propStatic->setValue($cdn, array('1' => '2'));
        $method = $this->getAccessibleMethod($cdn, 'getPathReplacements');
        $arResult = $method->invoke($cdn);

        $this->assertSame(
            array('1' => '2'),
            $arResult,
            'The static var'
        );
    }

    public function testGetPathReplacments()
    {
        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'Test1' => null,
            'Test2' => array('.abc', '.def'),
            'Test3' => null,
        );

        $cdn = new Netresearch_Cdn();
        $method = $this->getAccessibleMethod($cdn, 'getPathReplacements');

        $arResult = $method->invoke($cdn);
        $this->assertSame(
            array (
                0 => '/^(Test1\/[^?]*$)/',
                1 => '/^(Test2\/[^?]*(.abc|.def)$)/',
                2 => '/^(Test3\/[^?]*$)/',
            ),
            $arResult,
            'The static var'
        );
    }

    public function testGetPathReplacmentsWithIgnoringSlash()
    {
        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = true;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'Test1' => null,
            'Test2' => array('.abc', '.def'),
            'Test3' => null,
        );

        $cdn = new Netresearch_Cdn();
        $method = $this->getAccessibleMethod($cdn, 'getPathReplacements');

        $arResult = $method->invoke($cdn);
        $this->assertSame(
            array (
                0 => '/^\\/?(Test1\/[^?]*$)/',
                1 => '/^\\/?(Test2\/[^?]*(.abc|.def)$)/',
                2 => '/^\\/?(Test3\/[^?]*$)/',
            ),
            $arResult,
            'The static var'
        );
    }

    public function testGetContentReplacementsStaticIfSet()
    {
        $cdn = new Netresearch_Cdn();

        $propStatic = $this->getAccessibleProperty('Netresearch_Cdn', 'arContentReplacements');
        $propStatic->setValue($cdn, array('1' => '2'));
        $method = $this->getAccessibleMethod($cdn, 'getContentReplacements');
        $arResult = $method->invoke($cdn);

        $this->assertSame(
            array('1' => '2'),
            $arResult,
            'The static var'
        );
    }

    public function testGetContentReplacements()
    {
        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = false;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'Test1' => null,
            'Test2' => array('.abc', '.def'),
            'Test3' => null,
        );

        $cdn = new Netresearch_Cdn();
        $method = $this->getAccessibleMethod($cdn, 'getContentReplacements');

        $arResult = $method->invoke($cdn);
        $this->assertSame(
            array (
                0 => '/\"\/?(Test1\/[^?"]*\")/',
                1 => '/\"\/?(Test2\/[^?"]*(\.abc|\.def)\")/',
                2 => '/\"\/?(Test3\/[^?"]*\")/',
            ),
            $arResult,
            'The static var'
        );
    }

    public function testDoNotAddCdnPrefixForPhpFiles()
    {
        $GLOBALS['TSFE']->tmpl->setup['config.']['nr_cdn.']['URL']
            = '//cdn.example.org/';

        $GLOBALS['CDN_CONF_VARS']['ignoreslash'] = true;
        $GLOBALS['CDN_CONF_VARS']['paths'] = array(
            'path' => array('.jpg', '.js'),
            'foo'  => null,
        );

        $strContent = <<<EOT
        This is an path to <img src="foo/To/image.jpg">
        This is an path to <img src="/path/To/image.jpg?123">
        This is an path to <img src="/path/To/image.jpg">
        <script>
        var image = "path/to/script.js";
        var image = "/path/to/script.php";
        var image = "not/to/script.jpg";
        </script>
EOT;

        $strExpected = <<<EOT
        This is an path to <img src="//cdn.example.org/foo/To/image.jpg">
        This is an path to <img src="/path/To/image.jpg?123">
        This is an path to <img src="//cdn.example.org/path/To/image.jpg">
        <script>
        var image = "//cdn.example.org/path/to/script.js";
        var image = "/path/to/script.php";
        var image = "not/to/script.jpg";
        </script>
EOT;

        $strResult = Netresearch_Cdn::addCdnPrefix($strContent);

        $this->assertSame($strExpected, $strResult);
    }
}
