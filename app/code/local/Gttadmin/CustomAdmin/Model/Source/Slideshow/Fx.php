<?php

class Gttadmin_CustomAdmin_Model_Source_Slideshow_Fx
{
    public function toOptionArray()
    {
        return array(
			array('value' => 'fade',			'label' => Mage::helper('customadmin')->__('Fade')),
			array('value' => 'zoom',			'label' => Mage::helper('customadmin')->__('Zoom')),
			array('value' => 'fadeZoom',		'label' => Mage::helper('customadmin')->__('Fade Zoom')),
			array('value' => 'curtainX',		'label' => Mage::helper('customadmin')->__('Curtain X')),
			array('value' => 'curtainY',		'label' => Mage::helper('customadmin')->__('Curtain Y')),
			array('value' => 'blindX',			'label' => Mage::helper('customadmin')->__('Blind X')),
			array('value' => 'blindY',			'label' => Mage::helper('customadmin')->__('Blind Y')),
			array('value' => 'blindZ',			'label' => Mage::helper('customadmin')->__('Blind Z')),
			array('value' => 'growX',			'label' => Mage::helper('customadmin')->__('Grow X')),
			array('value' => 'growY',			'label' => Mage::helper('customadmin')->__('Grow Y')),
			array('value' => 'cover',			'label' => Mage::helper('customadmin')->__('Cover')),
			array('value' => 'uncover',			'label' => Mage::helper('customadmin')->__('Uncover')),
            array('value' => 'scrollHorz',		'label' => Mage::helper('customadmin')->__('Scroll Horizontal')),
            array('value' => 'scrollVert',		'label' => Mage::helper('customadmin')->__('Scroll Vertical')),
			array('value' => 'scrollUp',		'label' => Mage::helper('customadmin')->__('Scroll Up')),
			array('value' => 'scrollDown',		'label' => Mage::helper('customadmin')->__('Scroll Down')),
			array('value' => 'scrollLeft',		'label' => Mage::helper('customadmin')->__('Scroll Left')),
			array('value' => 'scrollRight',		'label' => Mage::helper('customadmin')->__('Scroll Right')),
			array('value' => 'slideX',			'label' => Mage::helper('customadmin')->__('Slide X')),
			array('value' => 'slideY',			'label' => Mage::helper('customadmin')->__('Slide Y')),
			array('value' => 'turnUp',			'label' => Mage::helper('customadmin')->__('Turn Up')),
			array('value' => 'turnDown',		'label' => Mage::helper('customadmin')->__('Turn Down')),
			array('value' => 'turnLeft',		'label' => Mage::helper('customadmin')->__('Turn Left')),
			array('value' => 'turnRight',		'label' => Mage::helper('customadmin')->__('Turn Right')),
			array('value' => 'wipe',			'label' => Mage::helper('customadmin')->__('Wipe')),
			array('value' => 'toss',			'label' => Mage::helper('customadmin')->__('Toss')),
			array('value' => 'shuffle',			'label' => Mage::helper('customadmin')->__('Shuffle'))
        );
    }
}