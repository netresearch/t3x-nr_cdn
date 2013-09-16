Netresearch Content Delivery Network Tools
==========================================

.. contents:: Table of Contents

What does it do
===============

This extension will link static media in your page to your Content Delivery Network
or just any other static server for media delivery.

http://en.wikipedia.org/wiki/Content_delivery_network

So you can use your full featured CDN or just use some lightweight fast
http server like Lighttpd to deliver content to your customers faster
and offload traffic from your CMS servers.

nr_cdn will rewrite URLs to static files in served pages e. g.:

- /fileadmin/upload/image.jpg => //cdn.example.org/fileadmin/upload/image.jpg
- /typo3conf/ext/my_ext/res/style.css => //cdn.example.org/typo3conf/ext/my_ext/res/style.css

TypoScript Configuration
========================

Constants
---------

::

    CDNURL = //cdn.domain.tld
    CDN_ignoreslash = 1

Setup
-----

::

    # CDN URL prefix/host
    URL = {$CDNURL}

    # whether to ignore leading slahes in given relacement paths
    ignoreslash = {$CDN_ignoreslash}

    paths {
        1 {
            # paths to be replaced/prefixed with CDN host
            path = fileadmin
        }
        2 {
            # paths to be replaced/prefixed with CDN host
            path = typo3temp
        }
        3 {
            # paths to be replaced/prefixed with CDN host
            path = typo3conf
            ext {
                # file extension to be replaced with CDN host
                10 = .js
                # file extension to be replaced with CDN host
                20 = .png
                # file extension to be replaced with CDN host
                30 = .gif
                # file extension to be replaced with CDN host
                40 = .jpg
            }
        }
    }

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

