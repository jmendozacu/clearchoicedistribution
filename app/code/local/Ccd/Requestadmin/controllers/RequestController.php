<?php
class Ccd_Requestadmin_RequestController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('requestadmin/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		define(_PAGESIZE,10);
		$totalRecordsNum		=	$this->getTotalRecordsNum();
		$totalNumberOfPages		=	ceil($totalRecordsNum/_PAGESIZE);
		$pageNumber				=	isset($_GET['page'])?$_GET['page']:1;
        $this->loadLayout();
		if($_GET['do']=='update' && isset($_GET['read_unread']) && isset($_GET['userId'])): 
					$userId			=	$_GET['userId'];
					$readUnread		=	$_GET['read_unread'];
					$sql = "update `request` SET `read` = '$readUnread' where `userid` =$userId";
					$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
					$status				=	$connection->query($sql);
		endif;
        //create a text block with the name of "example-block"
        $pageSize		= _PAGESIZE;        
        $start			=	$pageNumber*$pageSize-$pageSize;
		$limit			=	$pageSize;
		$sql = "select * from `request` ORDER BY `created_time` DESC limit $start,$limit";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$status				=	$connection->fetchAll($sql);
			$request='';
		$request.='<div class="content-header">
						<h3 class="icon-head head-system-account">Customer Applications</h3>
					</div>

					<div class="entry-edit">
						<div class="entry-edit-head">
							<h4 class="icon-head head-edit-form fieldset-legend">Message Box</h4>
							<div class="form-buttons"></div>
						</div>
					<div id="base_fieldset" class="fieldset ">
					';
		$request.='<table width="100%">
					   <tr style="background:#666E73;color:white;">
						   <th style="text-align:center;">Customer Id</th>
						   <th style="text-align:center;">Name</th>
						   <th style="text-align:center;">Contact No.</th>
						   <th style="text-align:center;">Website</th>
						   <th style="text-align:center;">Business Desc.</th>
						   <th style="text-align:center;">Address</th>
						   <th style="text-align:center;">Request</th>
						   <th style="text-align:center;">Mark as</th>
						   <th style="text-align:center;">Action</th>
					   </tr>';
		$bgColor			=	'style="background:#FFFFFF"';

		foreach($status as $res){
			$userId			=	$res['userid'];
			$username		=	$res['username'];
			$userphone		=	$res['userphone'];
			$requestdata	=	$res['requestdata'];
			$read_unread	=	$res['read'];
			$website		= 	$res['website'];
			$address		=	$res['address'];
			$description	=	$res['description'];
			if($read_unread==0):
				$read_unread_display = 'Mark as Read';
				$read_unread_change	=	1;
			else:
				$read_unread_display = 'Mark as Unread';
				$read_unread_change	=	0;
			endif;
			if($read_unread==0){
				$bgColor	=	'style="background:#EDEAEA"';
			}
			else{
				$bgColor	=	'style="background:#FFFFFF"';
			}
			$customerEditUrl=	Mage::helper("adminhtml")->getUrl("adminhtml/customer/edit/",array("id"=>$userId));
			$request.="<tr $bgColor>
							<td>
								$userId
							</td>
							<td>
								$username
							</td>
							<td>
								$userphone
							</td>
							<td>
								$website
							</td>
							<td>
								$description
							</td>
							<td>
								$address
							</td>
							<td>
								$requestdata
							</td>
							<td>
									<a href='?userId=$userId&read_unread=$read_unread_change&do=update'><input type='button' style='cursor:pointer' value='$read_unread_display'></a>
							</td>
							<td>		
								<a href='$customerEditUrl'>Add/Remove to Group</a>
							</td>							
					</tr>";	
		}
		//*******************************Bellow code is for pagination *************************************
		
		$request.="<tr><td colspan='6' align='center'>";
				//Code for Pagination		
						$totalPage=$totalNumberOfPages;
						$page=$pageNumber;
						$url="";
						if($totalPage > 1){	
							//get previous link
							if($page!=1){
							$request.=" ";
							$request.="<a href='?page=1".$url."'>Start</a>";
							$request.=" ";
							$request.="<a href='?page=".($page-1)."".$url."'>Prev</a>";
							}

							//get pagination link
							if(($page-2)>=1){
							$request.=" ";
							$request.="<a href='?page=".($page-2)."".$url."'>"." ".($page-2)."</a>";
							}			
							if(($page-1)>=1){
							$request.=" ";
							$request.="<a href='?page=".($page-1)."".$url."'>"." ".($page-1)."</a>";
							}
							$request.=" ";
							$request.="<font size='4'><b>".$page."</b></font>";
							if(($page+1)<=$totalPage){
							$request.=" ";
							$request.="<a href='?page=".($page+1)."".$url."'>"." ".($page+1)."</a>";
							}

							if(($page+2)<=$totalPage){
							$request.=" ";
							$request.="<a href='?page=".($page+2)."".$url."'>"." ".($page+2)."</a>";
							}
							//get next link

							if($page!=$totalPage){
							$request.=" ";
							$request.="<a href='?page=".($page+1)."".$url."'>Next</a>";
							$request.=" ";
							$request.="<a href='?page=".($totalPage)."".$url."'>End</a>";
							}
						}		
					$request.="</td></tr>";
		
		//********************************pagination ends here**********************************************
		
		
		
		$request.="</table></div></div>";
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block')
        ->setText($request);
	
        $this->_addContent($block);

        $this->renderLayout();
	}
	public function getTotalRecordsNum(){
		$query			=	"select count(*) from `request`";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$status				=	$connection->fetchAll($query);	
		foreach($status as $key):
			$val		=	$key['count(*)'];
		endforeach;
		return($val);
	}
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('request/request')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('request_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('request/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('request/adminhtml_request_edit'))
				->_addLeft($this->getLayout()->createBlock('request/adminhtml_request_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('request')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('request/request');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('request')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('request')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('request/request');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $requestIds = $this->getRequest()->getParam('request');
        if(!is_array($requestIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($requestIds as $requestId) {
                    $request = Mage::getModel('request/request')->load($requestId);
                    $request->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($requestIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $requestIds = $this->getRequest()->getParam('request');
        if(!is_array($requestIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($requestIds as $requestId) {
                    $request = Mage::getSingleton('request/request')
                        ->load($requestId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($requestIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'request.csv';
        $content    = $this->getLayout()->createBlock('request/adminhtml_request_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'request.xml';
        $content    = $this->getLayout()->createBlock('request/adminhtml_request_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
