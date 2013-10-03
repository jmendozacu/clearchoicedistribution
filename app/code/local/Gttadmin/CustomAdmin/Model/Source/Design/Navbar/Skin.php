<?php

class Gttadmin_CustomAdmin_Model_Source_Design_Navbar_Skin
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'default',			'label' => Mage::helper('customadmin')->__('Default')),
			array('value' => 'pink',			'label' => Mage::helper('customadmin')->__('Pink')),
			array('value' => 'brown',			'label' => Mage::helper('customadmin')->__('Brown')),
			array('value' => 'grey',		'label' => Mage::helper('customadmin')->__('Grey')),
        );
    }
}