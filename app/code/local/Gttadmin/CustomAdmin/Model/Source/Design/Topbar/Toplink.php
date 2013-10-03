<?php

class Gttadmin_CustomAdmin_Model_Source_Design_Topbar_Toplink
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'default','label' => Mage::helper('customadmin')->__('Default')),
			array('value' => 'top_pink','label' => Mage::helper('customadmin')->__('Pink')),
			array('value' => 'top_brown','label' => Mage::helper('customadmin')->__('Brown')),
			array('value' => 'top_grey','label' => Mage::helper('customadmin')->__('Grey'))
        );
    }
}