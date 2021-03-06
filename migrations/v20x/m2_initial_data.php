<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2013 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\migrations\v20x;

use blitze\sitemaker\services\forum\admin;

/**
 * Initial schema changes needed for Extension installation
 */
class m2_initial_data extends \phpbb\db\migration\migration
{
	/**
	 * @inheritdoc
	 */
	public static function depends_on()
	{
		return array(
			'\blitze\sitemaker\migrations\converter\c2_update_data',
			'\blitze\sitemaker\migrations\v20x\m1_initial_schema'
		);
	}

	/**
	 * @inheritdoc
	 */
	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'create_forum_cat'))),

			array('config.add', array('sitemaker_last_changed', 0)),
			array('config.add', array('sitemaker_default_layout', '')),
			array('config.add', array('sitemaker_parent_forum_id', 0)),
			array('config.add', array('sitemaker_blocks_cleanup_gc', 604800)),
			array('config.add', array('sitemaker_blocks_cleanup_last_gc', 0, 1)),
			array('config.add', array('sitemaker_startpage_controller', '')),
			array('config.add', array('sitemaker_startpage_method', '')),
			array('config.add', array('sitemaker_startpage_params', '')),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function revert_data()
	{
		return array(
			array('config.remove', array('sitemaker_last_changed')),
			array('config.remove', array('sitemaker_default_layout')),
			array('config.remove', array('sitemaker_parent_forum_id')),
			array('config.remove', array('sitemaker_blocks_cleanup_gc')),
			array('config.remove', array('sitemaker_blocks_cleanup_last_gc')),
			array('config.remove', array('sitemaker_startpage_controller')),
			array('config.remove', array('sitemaker_startpage_method')),
			array('config.remove', array('sitemaker_startpage_params')),
		);
	}

	public function create_forum_cat()
	{
		if (!class_exists('acp_forums'))
		{
			include($this->phpbb_root_path . 'includes/acp/acp_forums.' . $this->php_ext);
		}

		$forum_data = array(
			'forum_type'	=> FORUM_CAT,
			'forum_name'	=> 'phpBB Sitemaker Extensions',
		);

		if (!empty($this->config['sitemaker_parent_forum_id']))
		{
			$forum_data['forum_id'] = (int) $this->config['sitemaker_parent_forum_id'];
		}

		$errors = admin::save($forum_data);

		if (!sizeof($errors))
		{
			$this->config->set('sitemaker_parent_forum_id', $forum_data['forum_id']);
		}
	}
}
