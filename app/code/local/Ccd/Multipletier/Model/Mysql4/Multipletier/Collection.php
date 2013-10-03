<?php

class Ccd_Multipletier_Model_Mysql4_Multipletier_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('multipletier/multipletier');
    }
}