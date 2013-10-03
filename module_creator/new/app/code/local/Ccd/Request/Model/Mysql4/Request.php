<?php

class Ccd_Request_Model_Mysql4_Request extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the request_id refers to the key field in your database table.
        $this->_init('request/request', 'request_id');
    }
}