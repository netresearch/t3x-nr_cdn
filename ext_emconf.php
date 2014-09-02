<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "nr_cdn".
 *
 * Auto generated 02-09-2014 11:29
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
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-4.6.99',
			'php' => '5.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'state' => 'stable',
	'version' => '2.0.2',
	'_md5_values_when_last_written' => 'a:20:{s:9:"build.xml";s:4:"687b";s:9:"ChangeLog";s:4:"7eac";s:32:"class.user_t3libpagerenderer.php";s:4:"2055";s:26:"class.ux_tslib_content.php";s:4:"c8af";s:21:"class.ux_tslib_fe.php";s:4:"84b0";s:16:"ext_autoload.php";s:4:"1612";s:12:"ext_icon.gif";s:4:"a459";s:17:"ext_localconf.php";s:4:"5811";s:14:"ext_tables.php";s:4:"9fa8";s:10:"README.rst";s:4:"e5de";s:23:"src/Netresearch/Cdn.php";s:4:"0052";s:50:"src/Netresearch/Cdn/HookCssFilelinksGetFileUrl.php";s:4:"c98d";s:20:"static/constants.txt";s:4:"1279";s:16:"static/setup.txt";s:4:"771b";s:19:"tests/bootstrap.php";s:4:"890a";s:20:"tests/dummy-cObj.php";s:4:"6d5b";s:26:"tests/PageRendererTest.php";s:4:"a0c5";s:17:"tests/phpunit.xml";s:4:"439e";s:23:"tests/UxContentTest.php";s:4:"7c16";s:29:"tests/Netresearch/CdnTest.php";s:4:"9e27";}',
);

?>