<?php

class Gttadmin_CustomAdmin_Model_ThemeAdmin extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('customadmin/customadmin');
    }
}