<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2015 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\tests\model\blocks\collections;

class routes_test extends \phpbb_test_case
{
	/**
	 * Define the extension to be tested.
	 *
	 * @return string[]
	 */
	protected static function setup_extensions()
	{
		return array('blitze/sitemaker');
	}

	/**
	 * Test that required fields start with a null
	 */
	function test_collection()
	{
		$collection = new \blitze\sitemaker\model\blocks\collections\routes;

		$this->assertFalse($collection->valid());

		for ($i = 0; $i < 3; $i++)
		{
			$collection[$i] = new \blitze\sitemaker\model\blocks\entity\route(array('route_id' => $i + 1));
		}

		$this->assertTrue($collection->valid());
		$this->assertEquals(3, $collection->count());

		$this->assertEquals(1, $collection->current()->get_route_id());
		$this->assertEquals(2, $collection->next()->get_route_id());

		$collection->rewind();
		$this->assertEquals(0, $collection->key());

		$this->assertTrue($collection->offsetExists(1));

		$route = $collection->offsetGet(1);
		$this->assertEquals(2, $route->get_route_id());
		$this->assertTrue($collection->offsetUnset($route));
		$this->assertTrue($collection->offsetUnset(0));
		$this->assertNull($collection->offsetGet(0));

		$routes = $collection->get_entities();
		$this->assertEquals(1, sizeof($routes));

		$collection->clear();
		$this->assertFalse($collection->valid());
	}

	function test_adding_invalid_entity()
	{
		$collection = new \blitze\sitemaker\model\blocks\collections\routes;

		$invalid_object = new \stdClass;

		try
		{
			$collection[] = $invalid_object;
			$this->fail('no exception thrown');
		}
		catch (\blitze\sitemaker\exception\unexpected_value $e)
		{
			$this->assertEquals('INVALID_ENTITY', $e->getMessage());
		}
	}
}
