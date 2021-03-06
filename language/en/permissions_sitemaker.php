<?php
/**
*
* @package phpBB Sitemaker [English]
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Admin Permissions
$lang = array_merge($lang, array(
	'ACL_A_SM_MANAGE_BLOCKS'	=> 'Can manage blocks',
	'ACL_CAT_SITEMAKER'			=> 'Sitemaker',
));
