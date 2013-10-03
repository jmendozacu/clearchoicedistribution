<?php

class GTT_CustomZoom_Model_Source_Position
{
	public static function toOptionArray()
	{
		$list = array(
					"left" => Mage::Helper('CustomZoom')->__('left'),
					"right" => Mage::Helper('CustomZoom')->__('right'),
					"top" => Mage::Helper('CustomZoom')->__('top'),
                    "bottom" => Mage::Helper('CustomZoom')->__('bottom'),
                    "inside" => Mage::Helper('CustomZoom')->__('inside'),
					);
		
		return ($list);
	}
}