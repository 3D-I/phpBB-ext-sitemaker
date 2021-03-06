<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2013 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\blocks;

use blitze\sitemaker\services\menus\menu_block;

/**
* Menu Block
* @package phpBB Sitemaker Main Menu
*/
class menu extends menu_block
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \blitze\sitemaker\services\menus\display */
	protected $tree;

	/**
	 * {@inheritdoc}
	 */
	public function get_config(array $settings)
	{
		$menu_options = $this->get_menu_options();
		$depth_options = $this->get_depth_options();

		return array(
			'legend1'       => $this->user->lang('SETTINGS'),
			'menu_id'		=> array('lang' => 'MENU', 'validate' => 'int', 'type' => 'select', 'options' => $menu_options, 'default' => 0, 'explain' => false),
			'expanded'		=> array('lang' => 'EXPANDED', 'validate' => 'bool', 'type' => 'checkbox', 'options' => array(1 => ''), 'default' => 0, 'explain' => false),
			'max_depth'		=> array('lang' => 'MAX_DEPTH', 'validate' => 'int', 'type' => 'select', 'options' => $depth_options, 'default' => 3, 'explain' => false),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function display(array $db_data, $editing = false)
	{
		$title = $this->user->lang('MENU');
		$menu_id = $db_data['settings']['menu_id'];

		$data = $this->_get_menu($menu_id);

		if (!sizeof($data))
		{
			return array(
				'title'		=> $title,
				'content'	=> $this->_get_message($menu_id, $editing),
			);
		}

		$this->tree->set_params($db_data['settings']);
		$this->tree->display_navlist($data, $this->ptemplate, 'tree');
		$this->tree->generate_breadcrumb($data);

		return array(
			'title'     => $title,
			'content'   => $this->ptemplate->render_view('blitze/sitemaker', 'blocks/menu.html', 'menu_block'),
		);
	}

	/**
	 * @return array
	 */
	protected function get_depth_options()
	{
		$options = array();
		for ($i = 3; $i < 10; $i++)
		{
			$options[$i] = $i;
		}

		return $options;
	}
}
