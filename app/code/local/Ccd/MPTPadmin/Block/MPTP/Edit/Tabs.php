<?php

class Ccd_MPTPadmin_Block_MPTP_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mptpadmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mptp')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mptp')->__('Item Information'),
          'title'     => Mage::helper('mptp')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('mptp/adminhtml_mptp_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
