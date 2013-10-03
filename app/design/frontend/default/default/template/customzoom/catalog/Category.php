<?php
 
class GTT_CustomZoom_Block_System_Config_Category extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('category', array(
            'label' => Mage::helper('adminhtml')->__('Category'),
            'style' => 'width:120px',
            'renderer' => Mage::getBlockSingleton('CustomZoom/system_config_renderer_categories')
        ));
        $this->addColumn('fix_size', array(
            'label' => Mage::helper('adminhtml')->__('Fixed dimension'),
            'style' => 'width:120px',
            'renderer' => Mage::getBlockSingleton('CustomZoom/system_config_renderer_fixedsize')
        ));
        $this->addColumn('image_size', array(
            'label' => Mage::helper('adminhtml')->__('Value'),
            'style' => 'width:70px'
        ));
        $this->addColumn('apply_child', array(
            'label' => Mage::helper('adminhtml')->__('Apply to child'),
            'style' => 'width:70px',
            'renderer' => Mage::getBlockSingleton('CustomZoom/system_config_renderer_yesno')
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Category');
        parent::__construct();

        $this->setTemplate('CustomZoom/sysconfig/array.phtml');
    }
}