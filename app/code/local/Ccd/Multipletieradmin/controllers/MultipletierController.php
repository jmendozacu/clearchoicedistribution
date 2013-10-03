<?php

class Ccd_Multipletieradmin_MultipletierController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('multipletieradmin/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		//**************************REQUEST TO DROP A GROUP ***********************//
		$groupIdToDrop					=	$_POST['groupToBeDeleted'];
		if(isset($groupIdToDrop)){
			//before dropping a group we have to 1. delte related entries in product table 2. Then delete group
			$deleteStatus				=	$this->deleteProductsInGroup($groupIdToDrop);
			if($deleteStatus){
				$groupDeleteStatus		=	$this->deleteSelectedGroup($groupIdToDrop);
			}
			if($groupDeleteStatus){
				$request.='	<ul class="messages">
				<li class="success-msg">
					<ul>
						<li>
						<span>Group Has Been Deleted</span>
						</li>
					</ul>
				</li>
			</ul>';
			}
			else{
				$request.='	<ul class="messages">
					<li class="error-msg">
						<ul>
							<li>
							<span>Something went wrong</span>
							</li>
						</ul>
					</li>
				</ul>';
			}
		}		
		//*************************REQUEST TO DROP A GROUP ENDS HERE**************//
		
		//*************************REQUEXT TO CREATE A NEW GROUP *****************//
		$nameOfNewGroup				=	$_POST['nameOfNewGroup'];
		if(isset($nameOfNewGroup)){
			$createStatus			=	$this->createNewGroup($nameOfNewGroup);
			if($createStatus){
				$request.='	<ul class="messages">
				<li class="success-msg">
					<ul>
						<li>
						<span>Group Has Been Created</span>
						</li>
					</ul>
				</li>
			</ul>';				
			}
			else{
				$request.='	<ul class="messages">
					<li class="error-msg">
						<ul>
							<li>
							<span>Something Went Wrong. (Possible Reason: Group Name Already Exists)</span>
							</li>
						</ul>
					</li>
				</ul>';				
			}
		}
		//***********************REQUEXT TO CREATE A NEW GROUP ENDS HERE**********//
		
		//************** Access product sku's from ajax request ******************//				
		$groupId			=	$_REQUEST['group_id'];
		if(isset($groupId)){
			if($groupId!=0){
				echo $productSku		=	$this->getProductSku($groupId);
				die;
			}
		}
		//************** Access product sku's from ajax request Ends Here ******************//
		
		//************** Add new products to Groups*****************************************//
		$groupIdForm		=	$_POST['groupIdForm'];
		$groupProductForm	=	$_POST['productListTextarea'];
		if(isset($groupIdForm) && isset($groupProductForm)){
			//We got the sku comma separated string now we need to convert it into array
			$productArr		=	array();
			$productArr		=	explode(',',$groupProductForm);
			
			// We got the product array now need to check if all sku's are valid.
			$checkProductSkuCorrectness	=	$this->checkSku($productArr);
			if($checkProductSkuCorrectness=='valid' || empty($groupProductForm)){
				//We need to check if product exists in other groups
				$returnedSku		=	$this->checkGroups($groupIdForm,$productArr);	
				if(count($returnedSku)==count($productArr)){	
					$successStatus	=	$this->addProducts($groupIdForm,$productArr);	
				}
				else{
					$successStatus	=	$this->addProducts($groupIdForm,$returnedSku);
					$fewSkuSkipped	=	'skipped';
				}	
				if($successStatus){	
					if($fewSkuSkipped=='skipped'){						
						$request.='	<ul class="messages">
										<li class="success-msg">
											<ul>
												<li>
												<span>Few products are skipped because they are already in other Groups</span>
												</li>
											</ul>
										</li>
									</ul>';
						}
						else{
						$request.='	<ul class="messages">
										<li class="success-msg">
											<ul>
												<li>
												<span>Products Added Successfully.</span>
												</li>
											</ul>
										</li>
									</ul>';					
						}
				}
				else{
							$request.='	<ul class="messages">
							<li class="error-msg">
								<ul>
									<li>
									<span>Something went wrong.</span>
									</li>
								</ul>
							</li>
						</ul>';
				}
			}else{
				$request.= '<ul class="messages">
								<li class="error-msg">
									<ul>
										<li>
											<span>Following SKU\'s were invalid:</span>
										</li>
										<li>
											<span>
												';
												foreach($checkProductSkuCorrectness as $errorSku){
													$request.='
													'.$errorSku.',
													';
												}
					$request.='				</span>
										</li>
									</ul>
								</li>
							</ul>';
			}
		}
		
		//************** Add new products to Groups ENDS HERE *********************************//		

		$this->loadLayout();
		/* ****************Custom code started Here************* */
		$currentUrl = Mage::helper('core/url')->getCurrentUrl();
		$request.='<div class="content-header">
						<h3 class="icon-head head-system-account">Multiple Product Tier Pricing</h3>
					</div>

					<div class="entry-edit" style="float:left;width:50%;">
						<div class="entry-edit-head">
							<h4 class="icon-head head-edit-form fieldset-legend">Add/Remove Products From Group</h4>
							<div class="form-buttons"></div>
						</div>
						<div id="base_fieldset" class="fieldset" style="margin-left:auto;margin-right:auto; text-align:center;">
							<form method="POST" action="'.$currentUrl.'" onSubmit ="return validateForm();">
								 <input name="form_key" type="hidden" value='.Mage::getSingleton("core/session")->getFormKey().' /> 
								<table style="margin:auto;">
									<tr>
										<th style="background:#6F8992;color:#ffffff;">Select Group</th>
									</tr>
									<tr>
										<td style="height:30px;">
											<select style="width:100%;" class="groups" name="groupIdForm" onChange="loadProducts()">
												<option value="0">Select a Group...</option>
											';
												$groups			=	$this->getGroups();
												foreach($groups as $val){
													$request.= '<option value='.$val["cat_id"].'>'.$val["cat_name"].'</option>';
												}
			$request.='									
											</select>
										</td>
									</tr>
									<tr>
										<th style="background:#6F8992;color:#ffffff;">
											Product SKU\'s
										</th>
									</tr>
									<tr>
										<td>
											<textarea class="productList" style="width:360px;height:160px;" name="productListTextarea" placeholder="Enter Product SKU\'s in Comma separated format"></textarea>	
										</td>
									</tr>
									<tr>
										<td><input type="submit" value="Save" style="    background: none repeat scroll 0 0 #6F8992;
											border: 3px double white;
											color: white;
											cursor: pointer;
											vertical-align: middle;
											width: 75px;">
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div>
					';
		$request.='	<div class="entry-edit" style="float:left;width:48%;margin-left:2px;">
						<div class="entry-edit-head">
							<h4 class="icon-head head-edit-form fieldset-legend">Add/Remove Groups</h4>
							<div class="form-buttons"></div>
						</div>
						<div id="base_fieldset" class="fieldset" style="margin-left:auto;margin-right:auto; text-align:center;">
								<table style="margin:auto;">
									<tr>
										<th style="background:#6F8992;color:#ffffff;" colspan=3>Add a New Group</th>
									</tr>
									<tr>
										<form method="POST" action="'.$currentUrl.'" onSubmit="return validateGroupName();">
											<input name="form_key" type="hidden" value='.Mage::getSingleton("core/session")->getFormKey().' />									
											<td>Enter Group Name</td>
											<td><input type="text" name="nameOfNewGroup" id="nameOfNewGroup"></td>
											<td><input type="submit" value="Create"style="    background: none repeat scroll 0 0 #6F8992;
													border: 3px double white;
													color: white;
													cursor: pointer;
													vertical-align: middle;
													width: 75px;">
											</td>
										</form>
									</tr>
									<tr>
										<th style="background:#6F8992;color:#ffffff;" colspan=3>Delete Groups</th>
									</tr>
									<tr>
										<td colspan=3>
											<div style="height:200px;overflow:auto;">
												<table width=100%;>
													<tr>
														<th>Group Name</th>
														<th width="20%">Action</th>
													</tr>';
													foreach($groups as $val){
													
													$request.='
													<form method="POST" action="'.$currentUrl.'" onSubmit="return askForConfirmation();">
														<input name="form_key" type="hidden" value='.Mage::getSingleton("core/session")->getFormKey().' /> 
														<tr style="background:silver;">
																<td style="text-align:left;">'.$val["cat_name"].'</td>
																<input type="hidden" name="groupToBeDeleted" value="'.$val["cat_id"].'">
															<td>
																<input type="submit" value="Drop" style="    background: none repeat scroll 0 0 #6F8992;
																	border: 3px double white;
																	color: white;
																	cursor: pointer;
																	vertical-align: middle;
																	width: 75px;">
															</td>														
														</tr>
													</form>';
													}	

								$request.='	</table>
											</div>
										</td>
									</tr>								
								</table>
						</div>
					</div>	
					';
		$request.='<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>	
		<script type="text/javascript">

			
				var jQ  = $.noConflict();
			function validateForm(){
				if(jQ("select.groups").val()==0){
					alert("Please select a group");
					return false;
				}
			}
			function validateGroupName(){
				
				if(jQ("#nameOfNewGroup").val()=="" || jQ("#nameOfNewGroup").val()==" "){
					alert("Please enter a valid Name");
					return(false);
				}
			}
			function askForConfirmation(){
				var action=confirm("Do you really want to Drop this Group??");
				if(action==true){
					return(true);
				}
				else{
					return(false);
				}
			}	
			function loadProducts(){
				
				var val	= jQ("select.groups").val();
				if(val!=0){
				jQ.ajax({
					type:"GET",
					url:"'.$currentUrl.'?group_id="+val
					}).done(function(html){
						jQ(".productList").val(html);				
						});
				}
			}
		</script>';
		/* **************** Custom Code Ends Here ************* */
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block')
        ->setText($request);
	
        $this->_addContent($block);

        $this->renderLayout();
	}
	public function getGroups(){
		$query 		= 	"select * from `multiple_tier_cat`";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_read');
		$status		=	$connection->fetchAll($query);
		return($status);
	}
	public function getProductSku($groupId){
		$query 		= 	"select product_sku from `multipletier` where cat_id=$groupId";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_read');
		$status		=	$connection->fetchAll($query);
		foreach($status as $val){
				$arr[]	=$val['product_sku'];
		}
		$str			=	implode(',',$arr);
		return($str);
	}
	public function checkGroups($groupIdForm,$productArr){
		$productSkuList		=	array();
		foreach($productArr as $product){
			$query		=	"select count(cat_id) as result from `multipletier` where `product_sku` = '$product' and `cat_id` <> $groupIdForm";
			$connection = 	Mage::getSingleton('core/resource')->getConnection('core_read');
			$status		=	$connection->fetchAll($query);
			if($status[0]['result']==0){
				$productSkuList[]		= $product;
			}
			else{
				continue;
			}
			
		}
		return($productSkuList);
	}	
	public function addProducts($groupId,$productArr){
		$query 		= 	"delete from `multipletier` where cat_id=$groupId";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_write');
		$status		=	$connection->query($query);
		//This is done to check the case if user wants to remove all the products.
		$checkIfEmpty= implode(',',$productArr);
		if($status){
			if(!empty($checkIfEmpty)){
				foreach($productArr as $productSku){
					$query 		= 	"insert into `multipletier` (cat_id,product_sku) VALUES ($groupId,'$productSku')";
					$connection = 	Mage::getSingleton('core/resource')->getConnection('core_write');
					$status		=	$connection->query($query);		
				}
			}
		}
		return($status);
	}
	public function checkSku($productArr){
		$invalidLog			=	array();
		$status				=	'valid';
		foreach($productArr as $key=>$val){
			$id = Mage::getModel('catalog/product')->getIdBySku($val);
			if($id==null){
				$status				=	'error';
				$invalidLog[]		=	$val;
			}
			else{
				continue;
			}
		}
		if($status=='error'){
			return $invalidLog;
		}
		else{
			return $status;
		}
	}
	public function deleteProductsInGroup($groupIdToDrop){
		$query 		= 	"delete from `multipletier` where cat_id=$groupIdToDrop";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_write');
		$status		=	$connection->query($query);
		return($status);
	}
	public function deleteSelectedGroup($groupIdToDrop){
		$query 		= 	"delete from `multiple_tier_cat` where cat_id=$groupIdToDrop";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_write');
		$status		=	$connection->query($query);
		return($status);
	}
	public function createNewGroup($nameOfNewGroup){
		$nameOfNewGroup	=	trim($nameOfNewGroup);
		$query 		= 	"select * from `multiple_tier_cat` where cat_name = '$nameOfNewGroup'";
		$connection = 	Mage::getSingleton('core/resource')->getConnection('core_read');
		$exists		=	$connection->fetchAll($query);
		if(count($exists)==0){		
			$query 		= 	"insert into `multiple_tier_cat` (cat_name) VALUES ('$nameOfNewGroup')";
			$connection = 	Mage::getSingleton('core/resource')->getConnection('core_write');
			$status		=	$connection->query($query);	
			return($status);
		}	
		else{
			return(false);
		}	
	}
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('multipletier/multipletier')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('multipletier_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('multipletier/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('multipletier/adminhtml_multipletier_edit'))
				->_addLeft($this->getLayout()->createBlock('multipletier/adminhtml_multipletier_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multipletier')->__('Item does not exist'));
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
	  			
	  			
			$model = Mage::getModel('multipletier/multipletier');		
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
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('multipletier')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('multipletier')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('multipletier/multipletier');
				 
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
        $multipletierIds = $this->getRequest()->getParam('multipletier');
        if(!is_array($multipletierIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($multipletierIds as $multipletierId) {
                    $multipletier = Mage::getModel('multipletier/multipletier')->load($multipletierId);
                    $multipletier->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($multipletierIds)
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
        $multipletierIds = $this->getRequest()->getParam('multipletier');
        if(!is_array($multipletierIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($multipletierIds as $multipletierId) {
                    $multipletier = Mage::getSingleton('multipletier/multipletier')
                        ->load($multipletierId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($multipletierIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'multipletier.csv';
        $content    = $this->getLayout()->createBlock('multipletier/adminhtml_multipletier_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'multipletier.xml';
        $content    = $this->getLayout()->createBlock('multipletier/adminhtml_multipletier_grid')
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
