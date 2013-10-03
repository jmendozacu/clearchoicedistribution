<?php
class Ccd_Multipletier_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/multipletier?id=15 
    	 *  or
    	 * http://site.com/multipletier/id/15 	
    	 */
    	/* 
		$multipletier_id = $this->getRequest()->getParam('id');

  		if($multipletier_id != null && $multipletier_id != '')	{
			$multipletier = Mage::getModel('multipletier/multipletier')->load($multipletier_id)->getData();
		} else {
			$multipletier = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($multipletier == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$multipletierTable = $resource->getTableName('multipletier');
			
			$select = $read->select()
			   ->from($multipletierTable,array('multipletier_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$multipletier = $read->fetchRow($select);
		}
		Mage::register('multipletier', $multipletier);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}