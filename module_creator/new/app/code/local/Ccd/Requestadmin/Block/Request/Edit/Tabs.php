<?php

class Ccd_Requestadmin_Block_Request_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('requestadmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('request')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('request')->__('Item Information'),
          'title'     => Mage::helper('request')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('request/adminhtml_request_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
