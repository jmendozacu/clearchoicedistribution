<?php

 
class GTT_CustomZoom_Block_System_Config_Renderer_Categories extends Mage_Core_Block_Abstract
{
    public function _toHtml()
    {
        return $this->renderCategories();
    }

    protected function renderCategories()
    {
        $activeCategories = array();

        $category = Mage::getModel('catalog/category');
        $storeCategories = $category->getCategories(2, 0, 'name');
         foreach ($storeCategories as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $hasActiveCategoriesCount = (count($activeCategories) > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $column = $this->getColumn();
        $html = '<select name="'.$this->getInputName().'" '.
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '" '.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '')
        .'>';

        foreach ($activeCategories as $category) {
            $html .= $this->_renderCategoryItem($category, 0);
        }
        $html .= "</select>";
        return $html;
    }

    protected function _renderCategoryItem($category, $deep)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
        } else {
            $children = $category->getChildren();
        }

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }

        // assemble list item with attributes
        $html[] = '<option value="'. $category->getEntityId() .'">';
        $html[] = str_repeat('-',$deep).$this->escapeHtml($category->getName());
        $html[] = '</option>';

        // render children
        $htmlChildren = '';
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryItem($child, $deep+1);

        }
        if (!empty($htmlChildren)) {
            $html[] = $htmlChildren;
        }
        $html = implode("", $html);
        return $html;
    }
}