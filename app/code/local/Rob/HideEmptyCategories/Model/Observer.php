<?php
/**
 * Hide Empty Categories
 *
 * @category    Rob
 * @package     Rob_HideEmptyCategories
 * @copyright   Copyright (c) 2011 Rob
 * @author      Rave
 */
 
/**
 * Event Observer
 *
 * @category    Rob
 * @package     Rob_HideEmptyCategories
 */
class Rob_HideEmptyCategories_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Remove hidden caegories from the collection
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategoryCollectionLoadAfter($observer)
    {
        if ($this->_isApiRequest()) return;
        $collection = $observer->getEvent()->getCategoryCollection();
        $this->_removeHiddenCollectionItems($collection);
    }
 
    /**
     * Remove hidden items from a product or category collection
     * 
     * @param Mage_Eav_Model_Entity_Collection_Abstract|Mage_Core_Model_Mysql4_Collection_Abstract $collection
     */
    public function _removeHiddenCollectionItems($collection)
    {
        // Loop through each category or product
        foreach ($collection as $key => $item)
        {
            // If it is a category
            if ($item->getEntityTypeId() == 3) {
				if($item->getId()== 96 || $item->getId()==14){
                if ($item->getProductCount() < 1) {
                    $collection->removeItemByKey($key);
                }
            }
            }
        }
    }
 
    /**
     * Return true if the reqest is made via the api
     *
     * @return boolean
     */
    protected function _isApiRequest()
    {
        return Mage::app()->getRequest()->getModuleName() === 'api';
    }
}
