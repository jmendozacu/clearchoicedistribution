<?php

class Ccd_Requestadmin_Block_Request_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'requestadmin';
        $this->_controller = 'requestadmin';
        
        $this->_updateButton('save', 'label', Mage::helper('request')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('request')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('request_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'request_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'request_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('request_data') && Mage::registry('request_data')->getId() ) {
            return Mage::helper('request')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('request_data')->getTitle()));
        } else {
            return Mage::helper('request')->__('Add Item');
        }
    }
}
