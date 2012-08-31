<?php

########################################################################
# Extension Manager/Repository config file for ext "nr_cdn".
#
# Auto generated 27-08-2012 09:09
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Netresearch Content Delivery Network Tools',
	'description' => 'Images and Medias to Content Delivery Network',
	'category' => 'fe',
	'author' => 'Alexander Opitz, Sebastian Mendel',
	'author_company' => 'Netresearch GmbH & Co. KG',
	'author_email' => 'alexander.opitz@netresearch.de, sebastian.mendel@netresearch.de',
	'shy' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-0.0.0',
			'php' => '5.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.13.1',
	'_md5_values_when_last_written' => 'a:21:{s:9:"ChangeLog";s:4:"afb2";s:10:"README.rst";s:4:"9454";s:9:"build.xml";s:4:"c0f9";s:32:"class.user_t3libpagerenderer.php";s:4:"5949";s:26:"class.ux_tslib_content.php";s:4:"b82a";s:21:"class.ux_tslib_fe.php";s:4:"c365";s:16:"ext_autoload.php";s:4:"696f";s:12:"ext_icon.gif";s:4:"a459";s:17:"ext_localconf.php";s:4:"3174";s:14:"ext_tables.php";s:4:"ce5f";s:23:"src/Netresearch/Cdn.php";s:4:"b50f";s:50:"src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php";s:4:"dd33";s:20:"static/constants.txt";s:4:"879f";s:16:"static/setup.txt";s:4:"c06d";s:26:"tests/PageRendererTest.php";s:4:"cb5e";s:23:"tests/UxContentTest.php";s:4:"7519";s:19:"tests/bootstrap.php";s:4:"d2d3";s:20:"tests/dummy-cObj.php";s:4:"e02d";s:17:"tests/phpunit.xml";s:4:"c1d6";s:29:"tests/Netresearch/CdnTest.php";s:4:"ed96";s:21:"tests/doc/README.html";s:4:"3093";}',
	'suggests' => array(
	),
);

?>