<?php
class Ccd_Requestadmin_Block_Adminhtml_Request extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'requestadmin';
    $this->_blockGroup = 'requestadmin';
    $this->_headerText = Mage::helper('requestadmin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('requestadmin')->__('Add Item');
    parent::__construct();
  }
}
