<?php
class Patrick_Gallery_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/gallery?id=15 
    	 *  or
    	 * http://site.com/gallery/id/15 	
    	 */
    	/* 
		$gallery_id = $this->getRequest()->getParam('id');

  		if($gallery_id != null && $gallery_id != '')	{
			$gallery = Mage::getModel('gallery/gallery')->load($gallery_id)->getData();
		} else {
			$gallery = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($gallery == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$galleryTable = $resource->getTableName('gallery');
			
			$select = $read->select()
			   ->from($galleryTable,array('gallery_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$gallery = $read->fetchRow($select);
		}
		Mage::register('gallery', $gallery);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}
