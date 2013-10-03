<?php
class Ccd_Multipletier_Block_Multipletier extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getMultipletier()     
     { 
        if (!$this->hasData('multipletier')) {
            $this->setData('multipletier', Mage::registry('multipletier'));
        }
        return $this->getData('multipletier');
        
    }
}