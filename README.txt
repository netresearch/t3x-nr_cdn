Typical usage from TypoScript:

Constants:

CDNURL = http://cdn.domain.tld

[globalString = _SERVER|HTTPS=on]
CDNURL = https://sslcdn.domain.tld
[global]

configuration:

config.nr_cdn.URL = {$CDNURL}

Don't forget to clear the cache afterwards.

What won't go to your CDN:

- thumbnails, cause this will be generated through Typo3s thump.php
- class files, so you shouldn't have problems with java/website interactions coused by XSS