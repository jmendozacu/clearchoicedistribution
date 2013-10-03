<?php

class GTT_CustomZoom_Model_Source_Images
{
	public static function toOptionArray()
	{
		$list = array(
					"base" => Mage::Helper('CustomZoom')->__('Base images'),
					"all" => Mage::Helper('CustomZoom')->__('All images'),
					);

		return ($list);
	}
}