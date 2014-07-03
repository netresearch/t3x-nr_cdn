<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "nr_cdn".
 *
 * Auto generated 03-07-2014 15:49
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

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
	'version' => '1.2.0',
	'_md5_values_when_last_written' => 'a:20:{s:9:"build.xml";s:4:"687b";s:9:"ChangeLog";s:4:"ad06";s:32:"class.user_t3libpagerenderer.php";s:4:"3b4d";s:26:"class.ux_tslib_content.php";s:4:"04ce";s:21:"class.ux_tslib_fe.php";s:4:"c641";s:16:"ext_autoload.php";s:4:"6f54";s:12:"ext_icon.gif";s:4:"a459";s:17:"ext_localconf.php";s:4:"1512";s:14:"ext_tables.php";s:4:"185b";s:10:"README.rst";s:4:"dfa9";s:23:"src/Netresearch/Cdn.php";s:4:"8766";s:50:"src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php";s:4:"624d";s:20:"static/constants.txt";s:4:"1279";s:16:"static/setup.txt";s:4:"771b";s:19:"tests/bootstrap.php";s:4:"af3c";s:20:"tests/dummy-cObj.php";s:4:"6d5b";s:26:"tests/PageRendererTest.php";s:4:"a0c5";s:17:"tests/phpunit.xml";s:4:"439e";s:23:"tests/UxContentTest.php";s:4:"7c16";s:29:"tests/Netresearch/CdnTest.php";s:4:"9e27";}',
	'suggests' => array(
	),
);

?>