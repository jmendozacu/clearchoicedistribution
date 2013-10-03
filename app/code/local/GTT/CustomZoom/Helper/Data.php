<?php

class GTT_CustomZoom_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function resizeImage($_product, $_image, $_fixsize, $_imgsize) {

        $_originalSizes = getimagesize($_image->getPath());

        switch($_fixsize) {
            case "width":
                    $width = $_imgsize;
                    $height = $_originalSizes[1]/$_originalSizes[0]*$width;
                break;
            case "height":
                    $height = $_imgsize;
                    $width = $_originalSizes[0]/$_originalSizes[1]*$height;
                break;
            case "auto":
                    if ($_originalSizes[0] < $_originalSizes[1]) {
                        $height = $_imgsize;
                        $width = $_originalSizes[0]/$_originalSizes[1]*$height;
                    } else {
                        $width = $_imgsize;
                        $height = $_originalSizes[1]/$_originalSizes[0]*$width;
                    }
                break;
            case "both":
                    $height = $_imgsize;
                    $width = $_imgsize;
                break;
        }
        return Mage::helper('catalog/image')->init($_product,'image',$_image->getFile())->resize($width, $height);
    }

    public function imageToVarien($_product) {
		$images = $_product->getMediaGalleryImages();
		if(count($images)){
			$image = $images->getFirstItem(); 
			foreach ($images as $_image){
				if($_image->getFile() == $_product->getImage()){
					return $_image;
				}
			}
			return $image;
		} else {
			$images = $_product->getMediaGallery('images');			
			$image = $images[0];
			foreach ($images as $_image){
				if($_image['file'] == $_product->getImage()){
					$image = $_image;
				}
			}			
			$image['url'] = $_product->getMediaConfig()->getMediaUrl($image['file']);
			$image['id'] = isset($image['value_id']) ? $image['value_id'] : null;
			$image['path'] = $_product->getMediaConfig()->getMediaPath($image['file']);			
			return new Varien_Object($image);
		}
    }

    public function getConfig() {

        $CustomZoom['fixSize'] = Mage::getStoreConfig('GTT_CustomZoom/general/fix_size'); // width or height
        $CustomZoom['imgSize'] = Mage::getStoreConfig('GTT_CustomZoom/general/image_size'); // 265px by default

        $cat_configs = unserialize(Mage::getStoreConfig('GTT_CustomZoom/category/options'));
        $priopity = true;
        foreach($cat_configs as $_config) {
            if (Mage::registry('current_category')) {
                if ( (in_array($_config['category'], Mage::registry('current_category')->getPathIds())
                        && $_config['apply_child'] && $priopity)
                        || $_config['category'] == Mage::registry('current_category')->getEntityId() )
                {
                    $CustomZoom['fixSize'] = $_config['fix_size']; // width or height
                    $CustomZoom['imgSize'] = $_config['image_size']; // 265px by default
                    if ($_config['category'] == Mage::registry('current_category')->getEntityId()) {
                        // Priority for the current category of parents
                        $priopity = false;
                    }
                }
            }
        }
        $CustomZoom['displayImages'] = Mage::getStoreConfig('GTT_CustomZoom/configurable/displayimages');

        $effect = Mage::getStoreConfig('GTT_CustomZoom/general/effect');
        $shade = Mage::getStoreConfig('GTT_CustomZoom/general/shade');
        $CustomZoom['conf'] = "zoomWidth:'".Mage::getStoreConfig('GTT_CustomZoom/general/zoom_width')."', ".
                "zoomHeight:'".Mage::getStoreConfig('GTT_CustomZoom/general/zoom_height')."', ".
                "position:'".Mage::getStoreConfig('GTT_CustomZoom/general/position')."', ".
                "adjustX:".Mage::getStoreConfig('GTT_CustomZoom/general/adjust_x').", ".
                "adjustY:".Mage::getStoreConfig('GTT_CustomZoom/general/adjust_y').", ".
                ($effect=="shade"?"shade:'".$shade."', "."shadeOpacity:".Mage::getStoreConfig('GTT_CustomZoom/general/shade_opacity').", ":"").
                ($effect=="focus"?"softFocus:'true', ":"").
                "lensOpacity:".Mage::getStoreConfig('GTT_CustomZoom/general/lens_opacity').", ".
                "smoothMove:".Mage::getStoreConfig('GTT_CustomZoom/general/smooth_move').", ".
                "showTitle:'".(Mage::getStoreConfig('GTT_CustomZoom/general/show_title')?"true":"false")."', ".
                "titleOpacity:".Mage::getStoreConfig('GTT_CustomZoom/general/title_opacity');
        return $CustomZoom;
    }

    public function getAttributeId() {
        return Mage::getStoreConfig('GTT_CustomZoom/configurable/attribute');
    }
}