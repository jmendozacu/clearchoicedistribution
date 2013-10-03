<?php

class GTT_CustomZoom_Model_Source_Fixed
{
	public static function toOptionArray()
	{
		$list = array(
                    "auto" => Mage::Helper('CustomZoom')->__('Auto'),            
                    "width" => Mage::Helper('CustomZoom')->__('Width'),
                    "height" => Mage::Helper('CustomZoom')->__('Height'),
                    "both" => Mage::Helper('CustomZoom')->__('Both'),            
					);
		
		return ($list);
	}
}