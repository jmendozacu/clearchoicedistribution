<?php

class Gttadmin_CustomAdmin_Model_Source_Design_Navbar_Level2Link
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'default','label' => Mage::helper('customadmin')->__('Default')),
			array('value' => 'sub_pink','label' => Mage::helper('customadmin')->__('Pink')),
			array('value' => 'sub_brown','label' => Mage::helper('customadmin')->__('Brown')),
			array('value' => 'sub_grey','label' => Mage::helper('customadmin')->__('Grey'))
        );
    }
}