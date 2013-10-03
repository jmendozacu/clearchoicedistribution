<?php

class Ccd_Multipletieradmin_Block_Multipletier_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'multipletieradmin';
        $this->_controller = 'multipletieradmin';
        
        $this->_updateButton('save', 'label', Mage::helper('multipletier')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('multipletier')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('multipletier_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'multipletier_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'multipletier_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('multipletier_data') && Mage::registry('multipletier_data')->getId() ) {
            return Mage::helper('multipletier')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('multipletier_data')->getTitle()));
        } else {
            return Mage::helper('multipletier')->__('Add Item');
        }
    }
}
