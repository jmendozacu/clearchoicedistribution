<?php
class Ccd_Request_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/request?id=15 
    	 *  or
    	 * http://site.com/request/id/15 	
    	 */
    	/* 
		$request_id = $this->getRequest()->getParam('id');

  		if($request_id != null && $request_id != '')	{
			$request = Mage::getModel('request/request')->load($request_id)->getData();
		} else {
			$request = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($request == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$requestTable = $resource->getTableName('request');
			
			$select = $read->select()
			   ->from($requestTable,array('request_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$request = $read->fetchRow($select);
		}
		Mage::register('request', $request);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}