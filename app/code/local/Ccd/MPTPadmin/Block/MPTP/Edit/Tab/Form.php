<?php

class Ccd_MPTPadmin_Block_MPTP_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('mptp_form', array('legend'=>Mage::helper('mptp')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('mptp')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('mptp')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('mptp')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('mptp')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('mptp')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('mptp')->__('Content'),
          'title'     => Mage::helper('mptp')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getMPTPData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMPTPData());
          Mage::getSingleton('adminhtml/session')->setMPTPData(null);
      } elseif ( Mage::registry('mptp_data') ) {
          $form->setValues(Mage::registry('mptp_data')->getData());
      }
      return parent::_prepareForm();
  }
}