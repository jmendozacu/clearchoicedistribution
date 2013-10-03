<?php
class GTT_CustomZoom_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function imagesAction() {
        $config = Mage::helper("CustomZoom")->getConfig();
        $productIds = $this->getRequest()->getParam("products");

        $i = 0;
        foreach ($productIds as $_id):
            $_product = Mage::getModel("catalog/product")->load($_id);
            switch ($config['displayImages']):
                case "base":
                    $_image = Mage::helper("CustomZoom")->imageToVarien($_product);
                    $response[$i]['big'] = $_image->getUrl();
                    $response[$i]['small'] = Mage::helper("CustomZoom")->resizeImage($_product, $_image, $config['fixSize'], $config['imgSize'])->__toString();
                    $response[$i]['title'] = $_image->getLabel();
                    $response[$i]['thumb'] = Mage::helper('catalog/image')->init($_product, 'thumbnail', $_image->getFile())->resize(56)->__toString();
                    $i++;
                    break;
                case "all":
                    $galleryImages = $_product->getMediaGalleryImages();
                    if (count($galleryImages) > 0):
                        foreach ($galleryImages as $_image):
                            $response[$i]['big'] = $_image->url;
                            $response[$i]['small'] = Mage::helper("CustomZoom")->resizeImage($_product, $_image, $config['fixSize'], $config['imgSize'])->__toString();
                            $response[$i]['title'] = $_image->getLabel();
                            $response[$i]['thumb'] = Mage::helper('catalog/image')->init($_product, 'thumbnail', $_image->getFile())->resize(56)->__toString();
                            $i++;
                        endforeach;
                    endif;
                    break;
            endswitch; // f**king not cpp syntax )))
        endforeach;
        echo json_encode($response);
    }
}