<?php

class Ccd_Multipletier_Model_Mysql4_Multipletier extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the multipletier_id refers to the key field in your database table.
        $this->_init('multipletier/multipletier', 'multipletier_id');
    }
}