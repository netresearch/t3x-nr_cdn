<?php
class  tslib_cObj
{
    var $__USER = null;
    var $__TEXT = null;

    var $testUrlBefore = 'UNITTEST';


    function TEXT($conf)
    {
        return $this->__TEXT;
    }

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

?>