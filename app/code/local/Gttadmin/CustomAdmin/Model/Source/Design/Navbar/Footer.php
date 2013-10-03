<?php

class Gttadmin_CustomAdmin_Model_Source_Design_Navbar_Footer
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'white','label' => Mage::helper('customadmin')->__('White')),
			array('value' => 'Dark','label' => Mage::helper('customadmin')->__('Dark')),
        );
    }
}