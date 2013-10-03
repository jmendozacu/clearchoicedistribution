<?php


class GTT_CustomZoom_Model_Source_Attribute
{
	public static function toOptionArray()
	{
        $configProduct = Mage::getModel('catalog/product_type_configurable');
        $attributes = $configProduct->getSetAttributes(Mage::getModel('catalog/product'));

        foreach ($attributes as $attribute) {
            if ($configProduct->canUseAttribute($attribute)) {
                $list[$attribute->getAttributeId()] = $attribute->getFrontend()->getLabel();
            }
        }        

		return ($list);
	}
}