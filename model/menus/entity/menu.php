<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2015 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\model\menus\entity;

use blitze\sitemaker\model\base_entity;

/**
 * @method integer get_menu_id()
 * @method object set_menu_name($menu_name)
 * @method string get_menu_name()
 * @method object set_items(\blitze\sitemaker\model\menus\collections\items $items)
 * @method \blitze\sitemaker\model\menus\collections\items get_items()
 */
final class menu extends base_entity
{
	/** @var integer */
	protected $menu_id;

	/** @var string */
	protected $menu_name = '';

	/** @var \blitze\sitemaker\model\menus\collections\items */
	protected $items = array();

	/** @var array */
	protected $required_fields = array('menu_name');

	/** @var array */
	protected $db_fields = array(
		'menu_name',
	);

	/**
	 * Set menu ID
	 */
	public function set_menu_id($menu_id)
	{
		if (!$this->menu_id)
		{
			$this->menu_id = (int) $menu_id;
		}
		return $this;
	}

	public function set_menu_name($name)
	{
		$this->menu_name = ucwords(trim($name));
		return $this;
	}
}
