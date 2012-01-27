<?php

########################################################################
# Extension Manager/Repository config file for ext "nr_cdn".
#
# Auto generated 03-01-2012 12:16
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'NR CDN',
	'description' => 'Images and Medias to Content Delivery Network',
	'category' => 'fe',
	'author' => 'Alexander Opitz',
	'author_company' => 'Netresearch GmbH & Co.KG',
	'author_email' => 'alexander.opitz@netresearch.de',
	'shy' => '',
	'dependencies' => 'aida_common',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.2.1-0.0.0',
			'aida_autoloader' => '1.4.0-',
			'aida_common' => '0.0.1-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.9.7',
	'_md5_values_when_last_written' => 'a:14:{s:9:"ChangeLog";s:4:"2a1f";s:10:"README.txt";s:4:"4ae8";s:9:"build.xml";s:4:"0b0a";s:35:"class.user_cssstyledcontent_pi1.php";s:4:"e423";s:32:"class.user_t3libpagerenderer.php";s:4:"6905";s:26:"class.ux_tslib_content.php";s:4:"220c";s:21:"class.ux_tslib_fe.php";s:4:"0d2a";s:12:"ext_icon.gif";s:4:"a459";s:17:"ext_localconf.php";s:4:"dfac";s:26:"tests/PageRendererTest.php";s:4:"0f78";s:23:"tests/UxContentTest.php";s:4:"e850";s:19:"tests/bootstrap.php";s:4:"d2d3";s:20:"tests/dummy-cObj.php";s:4:"e02d";s:17:"tests/phpunit.xml";s:4:"da54";}',
	'suggests' => array(
	),
);

?>
