========================
TypoScript Configuration
========================

.. contents:: Inhaltsverzeichnis

TypoScript Configuration
========================

Constants
---------

::

    CDNURL = //cdn.domain.tld

Setup
-----

::

    config.nr_cdn.URL = {$CDNURL}

Note
----

Don't forget to clear the cache afterwards.

What won't go to your CDN:

- thumbnails, cause this will be generated through TYPO3s thump.php
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
            'fileadmin' => null, // every file
            'typo3temp' => null, // every file
            'typo3conf' => array('.js', '.png', '.gif', '.jpg'), // only static files
        ),
    );
