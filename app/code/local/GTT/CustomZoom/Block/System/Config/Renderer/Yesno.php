<?php

 
class GTT_CustomZoom_Block_System_Config_Renderer_Yesno extends Mage_Core_Block_Abstract
{
    public function _toHtml()
    {
        $column = $this->getColumn();
        $html = '<select name="'.$this->getInputName().'" '.
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '" '.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '')
        .'>';
        $html .= '<option value="0">No</option>';
        $html .= '<option value="1">Yes</option>';
        $html .= '</select>';
        return $html;
    }
}