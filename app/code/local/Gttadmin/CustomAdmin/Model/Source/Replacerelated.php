<?php

class Gttadmin_CustomAdmin_Model_Source_Replacerelated
{
    public function toOptionArray()
    {
        return array(
			array('value' => 0, 'label' => Mage::helper('customadmin')->__('Not Display CMS Block')),
            array('value' => 1, 'label' => Mage::helper('customadmin')->__('Always Replace with CMS Block')),
            array('value' => 2, 'label' => Mage::helper('customadmin')->__('Show CMS Block if No Related Products Available')),
        );
    }
}