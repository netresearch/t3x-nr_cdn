.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXT ROLES
.. --------------------------------------------------

.. role::   typoscript(code)

.. role::   ts(typoscript)
:class:  typoscript

.. role::   php(code)

.. highlight:: php

.. _start:

===========================================
Netresearch Content Delivery Network Tools
==========================================

.. only:: html

    :Classification:
        nr_cdn

    :Version:
        |release|

    :Language:
        en

    :Description:
        This extension will link static media in your page to your Content Delivery Network
        or just any other static server for media delivery.

        http://en.wikipedia.org/wiki/Content_delivery_network

        So you can use your full featured CDN or just use some lightweight fast
        http server like Lighttpd to deliver content to your customers faster
        and offload traffic from your CMS servers.

    :Keywords:
        netresearch, cdn, aws, cloudfront

    :Copyright:
        2010-2014

    :Author:
        Alexander Opitz, Sebastian Mendel

    :Email:
        alexander.opitz@netresearch.de, sebastian.mendel@netresearch.de

    :License:
        This document is published under the Open Content License
        available from http://www.opencontent.org/opl.shtml

    :Rendered:
        |today|


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

if you want you can exclude paths from rewriting with the help of the ignore_paths option.

TypoScript Configuration
========================

Constants
---------

::

    CDNURL = //cdn.domain.tld
    CDN_ignoreslash = 1

Setup
-----

.. code-block:: typoscript

    # CDN URL prefix/host
    URL = {$CDNURL}

    # whether to ignore leading slashes in given replacement paths
    ignoreslash = {$CDN_ignoreslash}

    ignore_paths {
        1 {
            # paths to be excluded from replacing/prefixed with CDN host
            path = fileadmin/no_cdn
        }
        2 {
            # paths to be excluded from replacing/prefix with CDN host
            path = fileadmin/no_cdn
            ext {
                # file extension to be excluded from replacement with CDN host
                10 = .js
                # file extension to be excluded from replacement with CDN host
                20 = .png
            }
        }

    }

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
- class files, so you shouldn't have problems with java/website interactions caused by XSS

PHP Configuration
=================

.. code-block:: php

    $GLOBALS['CDN_CONF_VARS'] = array(
        // host name for CDN
        'host' => 'cdn.example.org',

        // whether to ignore leading slashes in given replacement paths
        'ignoreslash' => true,

        // paths to ignore for replacement/prefix with CDN host
        'ignore_paths' => array(
            'fileadmin/no_cdn' => null, // ignore every file in no_cdn
            'fileadmin/no_cdn' => array('.js', '.png', '.gif', '.jpg'), // ignore only static files
        ),

        // paths to be replaced/prefixed with CDN host
        'paths' => array(
            'fileadmin' => null, // every file
            'typo3temp' => null, // every file
            'typo3conf' => array('.js', '.png', '.gif', '.jpg'), // only static files
        ),
    );

