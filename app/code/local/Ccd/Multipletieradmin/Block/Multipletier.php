<?php
class Ccd_Multipletieradmin_Block_Adminhtml_Multipletier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'multipletieradmin';
    $this->_blockGroup = 'multipletieradmin';
    $this->_headerText = Mage::helper('multipletieradmin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('multipletieradmin')->__('Add Item');
    parent::__construct();
  }
}
