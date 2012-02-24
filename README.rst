========================
TypoScript Configuration
========================

.. contents:: Inhaltsverzeichnis

Constants
---------

CDNURL = http://cdn.domain.tld

[globalString = _SERVER|HTTPS=on]
CDNURL = https://sslcdn.domain.tld
[global]

configuration
-------------

config.nr_cdn.URL = {$CDNURL}

Don't forget to clear the cache afterwards.

What won't go to your CDN:

- thumbnails, cause this will be generated through Typo3s thump.php
- class files, so you shouldn't have problems with java/website interactions coused by XSS

PHP Konfiguration
=================

::

    $GLOBALS['CDN_CONF_VARS'] = array(
        // host name for CDN
        'host' => 'cdn.example.org',

        // whether to ignore leading slahes in given relacement paths
        'ignoreslash' => true,

        // paths to be replaced/prefixed with CDN host
        'paths' => array(
            'fileadmin',
            'typo3temp',
            'webcam',
            'medien',
        ),
    );
