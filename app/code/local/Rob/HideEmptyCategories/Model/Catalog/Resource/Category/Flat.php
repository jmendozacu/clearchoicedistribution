<?php
/**
 * Hide Empty Categories
 *
 * @category    Rob
 * @package     Rob_HideEmptyCategories
 * @copyright   Copyright (c) 2011 Rob (http://Rob.com/)
 * @author      Josh Pratt (Rob)
 */
 
/**
 * Event Observer
 *
 * @category    Rob
 * @package     Rob_HideEmptyCategories
 */
class Rob_HideEmptyCategories_Model_Catalog_Resource_Category_Flat
    extends Mage_Catalog_Model_Resource_Category_Flat
{
    /**
     * Load nodes by parent id
     *
     * @param unknown_type $parentNode
     * @param integer $recursionLevel
     * @param integer $storeId
     * @return Mage_Catalog_Model_Resource_Category_Flat
     */
    protected function _loadNodes($parentNode = null, $recursionLevel = 0, $storeId = 0)
    {
        $nodes = parent::_loadNodes($parentNode, $recursionLevel, $storeId);
 
        foreach ($nodes as $node) {
            if ($node->getProductCount() == 0) {
                unset($nodes[$node->getId()]);
            }
        }
        return $nodes;
    }
}
