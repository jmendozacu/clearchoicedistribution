<?php
class Patrick_Gallery_Block_Gallery extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getGallery()     
     { 
        if (!$this->hasData('gallery')) {
            $this->setData('gallery', Mage::registry('gallery'));
        }
        return $this->getData('gallery');
        
    }
}
