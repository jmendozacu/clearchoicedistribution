<?php

class Ccd_MPTPadmin_Block_MPTP_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mptpadmin';
        $this->_controller = 'mptpadmin';
        
        $this->_updateButton('save', 'label', Mage::helper('mptp')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('mptp')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('mptp_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'mptp_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'mptp_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('mptp_data') && Mage::registry('mptp_data')->getId() ) {
            return Mage::helper('mptp')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('mptp_data')->getTitle()));
        } else {
            return Mage::helper('mptp')->__('Add Item');
        }
    }
}
