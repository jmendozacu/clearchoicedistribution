<?php

class GTT_CustomZoom_Model_Source_Effect
{
	public static function toOptionArray()
	{
		$list = array(
					"none" => Mage::Helper('CustomZoom')->__('None'),
					"shade" => Mage::Helper('CustomZoom')->__('Shade'),
					"focus" => Mage::Helper('CustomZoom')->__('Soft Focus')
					);

		return ($list);
	}
}