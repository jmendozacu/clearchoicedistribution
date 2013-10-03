<?php

 
class GTT_CustomZoom_Block_System_Config_Renderer_Fixedsize extends Mage_Core_Block_Abstract
{
    public function _toHtml()
    {
        $options = Mage::getModel("CustomZoom/source_fixed")->toOptionArray();

        $column = $this->getColumn();
        $html = '<select name="'.$this->getInputName().'" '.
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '" '.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '')
        .'>';
        foreach ($options as $value => $label)
        {
            $html .= '<option value="'.$value.'">'.$label.'</option>';
        }
        $html .= '</select>';
        return $html;
    }
}