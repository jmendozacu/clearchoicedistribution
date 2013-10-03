<?php

class Ccd_Multipletieradmin_Block_Multipletier_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('multipletieradmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('multipletier')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('multipletier')->__('Item Information'),
          'title'     => Mage::helper('multipletier')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('multipletier/adminhtml_multipletier_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
