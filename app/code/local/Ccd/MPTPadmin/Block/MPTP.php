<?php
class Ccd_MPTPadmin_Block_Adminhtml_MPTP extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'mptpadmin';
    $this->_blockGroup = 'mptpadmin';
    $this->_headerText = Mage::helper('mptpadmin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('mptpadmin')->__('Add Item');
    parent::__construct();
  }
}
