<?php
class Ccd_Request_Block_Request extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRequest()     
     { 
        if (!$this->hasData('request')) {
            $this->setData('request', Mage::registry('request'));
        }
        return $this->getData('request');
        
    }
}