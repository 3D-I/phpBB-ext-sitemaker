<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2013 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\services\menus\action;

class delete_menu extends base_action
{
	public function execute()
	{
		$menu_id = $this->request->variable('menu_id', 0);

		if (!$menu_id)
		{
			return array('errors' => $this->user->lang('MENU_NOT_FOUND'));
		}

		$menu_mapper = $this->mapper_factory->create('menus', 'menus');

		if (($entity = $menu_mapper->load(array('menu_id' => $menu_id))) === null)
		{
			return array('errors' => $this->user->lang('MENU_NOT_FOUND'));
		}

		$menu_mapper->delete($entity);

		return array(
			'id'	=> $entity->get_menu_id(),
		);
	}
}