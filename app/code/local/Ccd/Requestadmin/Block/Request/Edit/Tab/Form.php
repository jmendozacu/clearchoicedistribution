<?php

class Ccd_Requestadmin_Block_Request_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('request_form', array('legend'=>Mage::helper('request')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('request')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('request')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('request')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('request')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('request')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('request')->__('Content'),
          'title'     => Mage::helper('request')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getRequestData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRequestData());
          Mage::getSingleton('adminhtml/session')->setRequestData(null);
      } elseif ( Mage::registry('request_data') ) {
          $form->setValues(Mage::registry('request_data')->getData());
      }
      return parent::_prepareForm();
  }
}