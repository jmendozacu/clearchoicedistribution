<?php

class Gttadmin_CustomAdmin_Model_Source_Design_Cufon_Cufonfont
{
    public function toOptionArray()
    {
        return array(
			array('value' => '','label' => Mage::helper('customadmin')->__('Select Cufon')),
			array('value' => 'Caviar_Dreams_300.font','label' => Mage::helper('customadmin')->__('Caviar Dreams')),
			array('value' => 'Nobile_250.font','label' => Mage::helper('customadmin')->__('Nobile')),
			array('value' => 'Bebas_Neue_400.font','label' => Mage::helper('customadmin')->__('Bebas Neue')),
			array('value' => 'Euro_Caps_400.font','label' => Mage::helper('customadmin')->__('Euro Caps')),			
			array('value' => 'Jalane_light_300.font','label' => Mage::helper('customadmin')->__('Jalane light')),
			array('value' => 'Mentone_Lig_600.font','label' => Mage::helper('customadmin')->__('Mentone Lig')),						
			array('value' => 'Motor_Oil_1937_M54_400.font','label' => Mage::helper('customadmin')->__('Motor Oil')),									
			array('value' => 'Yanone_Kaffeesatz_Regular_400.font','label' => Mage::helper('customadmin')->__('Yanone Kaffeesatz Regular')),									

        );
    }
}