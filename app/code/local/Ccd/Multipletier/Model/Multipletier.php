<?php

class Ccd_Multipletier_Model_Multipletier extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('multipletier/multipletier');
    }
}