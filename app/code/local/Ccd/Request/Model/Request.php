<?php

class Ccd_Request_Model_Request extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('request/request');
    }
}