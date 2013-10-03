<?php

class Gttadmin_CustomAdmin_Model_Source_Slideshow_Easing
{
    public function toOptionArray()
    {
        return array(
			//Ease in-out
			array('value' => 'easeInOutSine',	'label' => Mage::helper('customadmin')->__('easeInOutSine')),
			array('value' => 'easeInOutQuad',	'label' => Mage::helper('customadmin')->__('easeInOutQuad')),
			array('value' => 'easeInOutCubic',	'label' => Mage::helper('customadmin')->__('easeInOutCubic')),
			array('value' => 'easeInOutQuart',	'label' => Mage::helper('customadmin')->__('easeInOutQuart')),
			array('value' => 'easeInOutQuint',	'label' => Mage::helper('customadmin')->__('easeInOutQuint')),
			array('value' => 'easeInOutExpo',	'label' => Mage::helper('customadmin')->__('easeInOutExpo')),
			array('value' => 'easeInOutCirc',	'label' => Mage::helper('customadmin')->__('easeInOutCirc')),
			array('value' => 'easeInOutElastic','label' => Mage::helper('customadmin')->__('easeInOutElastic')),
			array('value' => 'easeInOutBack',	'label' => Mage::helper('customadmin')->__('easeInOutBack')),
			array('value' => 'easeInOutBounce',	'label' => Mage::helper('customadmin')->__('easeInOutBounce')),
			//Ease out
			array('value' => 'easeOutSine',		'label' => Mage::helper('customadmin')->__('easeOutSine')),
			array('value' => 'easeOutQuad',		'label' => Mage::helper('customadmin')->__('easeOutQuad')),
			array('value' => 'easeOutCubic',	'label' => Mage::helper('customadmin')->__('easeOutCubic')),
			array('value' => 'easeOutQuart',	'label' => Mage::helper('customadmin')->__('easeOutQuart')),
			array('value' => 'easeOutQuint',	'label' => Mage::helper('customadmin')->__('easeOutQuint')),
			array('value' => 'easeOutExpo',		'label' => Mage::helper('customadmin')->__('easeOutExpo')),
			array('value' => 'easeOutCirc',		'label' => Mage::helper('customadmin')->__('easeOutCirc')),
			array('value' => 'easeOutElastic',	'label' => Mage::helper('customadmin')->__('easeOutElastic')),
			array('value' => 'easeOutBack',		'label' => Mage::helper('customadmin')->__('easeOutBack')),
			array('value' => 'easeOutBounce',	'label' => Mage::helper('customadmin')->__('easeOutBounce')),
			//Ease in
			array('value' => 'easeInSine',		'label' => Mage::helper('customadmin')->__('easeInSine')),
			array('value' => 'easeInQuad',		'label' => Mage::helper('customadmin')->__('easeInQuad')),
			array('value' => 'easeInCubic',		'label' => Mage::helper('customadmin')->__('easeInCubic')),
			array('value' => 'easeInQuart',		'label' => Mage::helper('customadmin')->__('easeInQuart')),
			array('value' => 'easeInQuint',		'label' => Mage::helper('customadmin')->__('easeInQuint')),
			array('value' => 'easeInExpo',		'label' => Mage::helper('customadmin')->__('easeInExpo')),
			array('value' => 'easeInCirc',		'label' => Mage::helper('customadmin')->__('easeInCirc')),
			array('value' => 'easeInElastic',	'label' => Mage::helper('customadmin')->__('easeInElastic')),
			array('value' => 'easeInBack',		'label' => Mage::helper('customadmin')->__('easeInBack')),
			array('value' => 'easeInBounce',	'label' => Mage::helper('customadmin')->__('easeInBounce')),
			//No easing
			array('value' => 'null',			'label' => Mage::helper('customadmin')->__('Disabled'))
        );
    }
}