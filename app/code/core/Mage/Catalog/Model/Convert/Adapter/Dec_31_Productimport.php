<?php
/**
* Product_import.php
* 
* @copyright  copyright (c) 2009 toniyecla[at]gmail.com
* @license    http://opensource.org/licenses/osl-3.0.php open software license (OSL 3.0)
*/

class Mage_Catalog_Model_Convert_Adapter_Productimport
extends Mage_Catalog_Model_Convert_Adapter_Product
 {
    private function _editTierPrices(&$product, $tier_prices_field = false)
    {
        
        if (($tier_prices_field) && !empty($tier_prices_field)) {
            
            if(trim($tier_prices_field) == 'REMOVE'){
            
                $product->setTierPrice(array());
            
            } else {
                
                //echo ('adding tier');
                //get current product tier prices
                $existing_tps = $product->getTierPrice();
                
                $etp_lookup = array();
                //make a lookup array to prevent dup tiers by qty
                foreach($existing_tps as $key => $etp){
                    $etp_lookup[intval($etp['price_qty'])] = $key;
                }
                
                //parse incoming tier prices string
                $incoming_tierps = explode('|',$tier_prices_field);
$tps_toAdd = array();                
foreach($incoming_tierps as $tier_str){
                    if (empty($tier_str)) continue;
                    
                    $tmp = array();
                    $tmp = explode('=',$tier_str);
                    
                    if ($tmp[0] == 0 && $tmp[1] == 0) continue;
                    
                    $tps_toAdd[$tmp[0]] = array(
                                        'website_id' => 0, // !!!! this is hard-coded for now
                                        'cust_group' => 1, // !!! so is this
                                        'price_qty' => $tmp[0],
                                        'price' => $tmp[1],
                                        'delete' => ''
                                    );
                                    
                    //drop any existing tier values by qty
                    if(isset($etp_lookup[intval($tmp[0])])){
                        unset($existing_tps[$etp_lookup[intval($tmp[0])]]);
                    }
                    
                }

                //combine array
                $tps_toAdd =  array_merge($existing_tps, $tps_toAdd);
				
                //save it
                $product->setTierPrice($tps_toAdd);
                //$product->setData('tier_price', $tps_toAdd);
foreach($incoming_tierps as $tier_str){
                    if (empty($tier_str)) continue;
                    
                    $tmp = array();
                    $tmp = explode('=',$tier_str);
                    
                    if ($tmp[0] == 0 && $tmp[1] == 0) continue;
                    
                    $tps_toAdd[$tmp[0]] = array(
                                        'website_id' => 0, // !!!! this is hard-coded for now
                                        'cust_group' => 2, // !!! so is this
                                        'price_qty' => $tmp[0],
                                        'price' => $tmp[1],
                                        'delete' => ''
                                    );
                                    
                    //drop any existing tier values by qty
                    if(isset($etp_lookup[intval($tmp[0])])){
                        unset($existing_tps[$etp_lookup[intval($tmp[0])]]);
                    }
                    
                }  

                //combine array
                $tps_toAdd =  array_merge($existing_tps, $tps_toAdd);
				
                //save it
                $product->setTierPrice($tps_toAdd);
                //$product->setData('tier_price', $tps_toAdd);                              
            }
            
        }
        
    }
  	
    /**
    * Save product (import)
    * 
    * @param array $importData 
    * @throws Mage_Core_Exception
    * @return bool 
    */
    public function saveRow( array $importData )    
    {
        $product = $this -> getProductModel();
        $product -> setData( array() );
        
        if ( $stockItem = $product -> getStockItem() ) {
            $stockItem -> setData( array() );
            } 
        
        if ( empty( $importData['store'] ) ) {
            if ( !is_null( $this -> getBatchParams( 'store' ) ) ) {
                $store = $this -> getStoreById( $this -> getBatchParams( 'store' ) );
                } else {
                $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" not defined', 'store' );
                Mage :: throwException( $message );
                } 
            } else {
            $store = $this -> getStoreByCode( $importData['store'] );
            } 
        
        if ( $store === false ) {
            $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, store "%s" field not exists', $importData['store'] );
            Mage :: throwException( $message );
            } 
        
        if ( empty( $importData['sku'] ) ) {
            $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" not defined', 'sku' );
            Mage :: throwException( $message );
            }
        //**********************Multiple Product Tier Pricing**************************************//
        if(!empty($importData['mptp_cat_id'])):
			$productSkuArr				=	array();
			//$productSkuArr				=	explode(',',$importData['Group_product_sku']);
			$mptpCatId					=	$importData['mptp_cat_id'];
			$connection					= 	Mage::getSingleton('core/resource')->getConnection('core_read');
			$query 						= "SELECT * FROM multiple_tier_cat where `cat_name`='$mptpCatId'";
			$catExistance				=	$connection->fetchAll($query);
			foreach($catExistance as $val){
				$mptpActualCatId		=	$val['cat_id'];
			}			
			if(count($catExistance)==0):
				echo 'mptp_cat_id mentioned in csv does\'nt exist. SKIPPING Product';
				die;
			endif;		
			$currentProductSku			=	$importData['sku'];
			$connection					= 	Mage::getSingleton('core/resource')->getConnection('core_read');
			$mptpUpdateStatus			=	$importData['mptp_update_status'];
							if($mptpUpdateStatus==1):
								$writeConnection 					= 	Mage::getSingleton('core/resource')->getConnection('core_write');
								$query								=	"delete from multipletier where `product_sku`= '$currentProductSku'";
								$res 								= 	$writeConnection->query($query);
								$writeConnection 					= 	Mage::getSingleton('core/resource')->getConnection('core_write');
								$query								=	"insert into multipletier (`cat_id`,`product_sku`)
																		values($mptpActualCatId,'$currentProductSku')";
								$res 								= 	$writeConnection->query($query);												
							else:
										$connection					= 	Mage::getSingleton('core/resource')->getConnection('core_read');
										$query 						= 	"SELECT * FROM multipletier where `product_sku`='$currentProductSku'";
										$preExistance				=	$connection->fetchAll($query);
										if(count($preExistance)==0):
											$writeConnection 					= 	Mage::getSingleton('core/resource')->getConnection('core_write');
											$query								=	"insert into multipletier (`cat_id`,`product_sku`)
																					values($mptpActualCatId,'$currentProductSku')";
											$res 								= 	$writeConnection->query($query);
										endif;
							endif;
		endif;				
			
		//********************Multiple Product Tier Pricing Ends Here*******************************//	
			
			      
        $product -> setStoreId( $store -> getId() );
        $productId = $product -> getIdBySku( $importData['sku'] );
        $new = true; // fix for duplicating attributes error
        if ( $productId ) {
            $product -> load( $productId );
            $new = false; // fix for duplicating attributes error
            } 
        $productTypes = $this -> getProductTypes();
        $productAttributeSets = $this -> getProductAttributeSets();
        
        // delete disabled products
        if ( $importData['status'] == 'Disabled' ) {
            $product = Mage :: getSingleton( 'catalog/product' ) -> load( $productId );
            $this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'image' ) ) );
            $this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'small_image' ) ) );
            $this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'thumbnail' ) ) );
            $media_gallery = $product -> getData( 'media_gallery' );
            foreach ( $media_gallery['images'] as $image ) {
                $this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $image['file'] ) );
                } 
            $product -> delete();
            return true;
            } 
        
        if ( empty( $importData['type'] ) || !isset( $productTypes[strtolower( $importData['type'] )] ) ) {
            $value = isset( $importData['type'] ) ? $importData['type'] : '';
            $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, is not valid value "%s" for field "%s"', $value, 'type' );
            Mage :: throwException( $message );
            } 
        $product -> setTypeId( $productTypes[strtolower( $importData['type'] )] );
        
        if ( empty( $importData['attribute_set'] ) || !isset( $productAttributeSets[$importData['attribute_set']] ) ) {
            $value = isset( $importData['attribute_set'] ) ? $importData['attribute_set'] : '';
            $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, is not valid value "%s" for field "%s"', $value, 'attribute_set' );
            Mage :: throwException( $message );
            } 
        $product -> setAttributeSetId( $productAttributeSets[$importData['attribute_set']] );
        
        foreach ( $this -> _requiredFields as $field ) {
            $attribute = $this -> getAttribute( $field );
            if ( !isset( $importData[$field] ) && $attribute && $attribute -> getIsRequired() ) {
                $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" for new products not defined', $field );
                Mage :: throwException( $message );
                } 
            } 
        
        if ( $importData['type'] == 'configurable' ) {
            $product -> setCanSaveConfigurableAttributes( true );
            $configAttributeCodes = $this -> userCSVDataAsArray( $importData['config_attributes'] );
            $usingAttributeIds = array();
            foreach( $configAttributeCodes as $attributeCode ) {
                $attribute = $product -> getResource() -> getAttribute( $attributeCode );
                if ( $product -> getTypeInstance() -> canUseAttribute( $attribute ) ) {
                    if ( $new ) { // fix for duplicating attributes error
                        $usingAttributeIds[] = $attribute -> getAttributeId();
                        } 
                    } 
                } 
            if ( !empty( $usingAttributeIds ) ) {
                $product -> getTypeInstance() -> setUsedProductAttributeIds( $usingAttributeIds );
                $product -> setConfigurableAttributesData( $product -> getTypeInstance() -> getConfigurableAttributesAsArray() );
                $product -> setCanSaveConfigurableAttributes( true );
                $product -> setCanSaveCustomOptions( true );
                } 
            if ( isset( $importData['associated'] ) ) {
                $product -> setConfigurableProductsData( $this -> skusToIds( $importData['associated'], $product ) );
                } 
            } 
        
        if ( isset( $importData['related'] ) ) {
            $linkIds = $this -> skusToIds( $importData['related'], $product );
            if ( !empty( $linkIds ) ) {
                $product -> setRelatedLinkData( $linkIds );
                } 
            }
		if ( isset(	$importData['limited_stock_qty']))
		 {
			$product -> setLimitedStock( $importData['limited_stock_qty'] );			 
		 }
		 if ( !empty($importData['Normal']) && !empty($importData['Wholesale']))
		 {
			 
			 //0: not logged in, 1: genral, 2: wholesaler
			$groupPricingData = array (
			  array ('website_id'=>0, 'cust_group'=>1, 'price'=>$importData['Normal']),
			  array ('website_id'=>0, 'cust_group'=>2, 'price'=>$importData['Wholesale']),
			  array ('website_id'=>0, 'cust_group'=>0, 'price'=>$importData['price'])
			  );
			$product->setData('group_price',$groupPricingData);			 
		 }	
		 if ( !empty($importData['Normal']) && empty($importData['Wholesale']))
		 {
			 //0: not logged in, 1: genral, 2: wholesaler
			$groupPricingData = array (
			  array ('website_id'=>0, 'cust_group'=>1, 'price'=>$importData['Normal']),
			  array ('website_id'=>0, 'cust_group'=>0, 'price'=>$importData['price'])
			  );
			$product->setData('group_price',$groupPricingData);			 
		 }	
		 if ( empty($importData['Normal']) && !empty($importData['Wholesale']))
		 {
			 //0: not logged in, 1: genral, 2: wholesaler
			$groupPricingData = array (
			  array ('website_id'=>0, 'cust_group'=>2, 'price'=>$importData['Wholesale']),
			  array ('website_id'=>0, 'cust_group'=>0, 'price'=>$importData['price'])
			  );
			$product->setData('group_price',$groupPricingData);			 
		 }		 	         
        if ( isset( $importData['upsell'] ) ) {
            $linkIds = $this -> skusToIds( $importData['upsell'], $product );
            if ( !empty( $linkIds ) ) {
                $product -> setUpSellLinkData( $linkIds );
                } 
            }
        if( !empty($importData['length'])){
			$product->setLength();
		}
        if( !empty($importData['width'])){
			$product->setWidth();
		} 
        if( !empty($importData['height'])){
			$product->setHeight();
		}
		if( !empty($importData['capacity'])){
			$product->setCapacity();
		}
		if(!empty($importData['warranty'])){
			$product->setWarrenty();
		} 
		if(!empty($importData['shipping_length'])){
			$product->setShippingLength();
		}
		if(!empty($importData['shipping_width'])){
			$product->setShippingWidth();
		}
		if(!empty($importData['shipping_height'])){
			$product->setShippingHeight();
		}						
        if( !empty($importData['description_specs'])){
			$product->setDescriptionSpecs();
		} 
		if( !empty($importData['description_related'])){
			$product->setDescriptionRelated();
		} 
		if( !empty($importData['description_related'])){
			$product->setDescriptionRelated();
		}
		if( !empty($importData['description_addon_title'])){
			$product->setDescriptionAddonTitle();
		}						         
//Here is code for testing the code why the product is not showing in the fronend.
/*
$mageFilename = 'app/Mage.php';
require_once $mageFilename;
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app('admin');
Mage::register('isSecureArea', 1);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);		
		
		$magentoPath = '/var/www/freshmage/';
		echo  $magentoPath . 'includes/config.php';
		require_once($magentoPath . 'includes/config.php');
		require_once($magentoPath . 'app/Mage.php');
		$storeID = 1;
		$websiteIDs = array(1);
		$cats = array("3");

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		Mage::app('default');
		$productCheck = new Mage_Catalog_Model_Product(); 
		Mage::register('product', $productCheck);
		$p = array(
		  'sku_type' => 0,
		  'sku' => '687',
		  'name' => "BarProduct",
		  'description' => 'Foo',
		  'short_description' => 'Bar',
		  'type_id' => 'bundle',
		  'attribute_set_id' => 4,
		  'weight_type' => 0,
		  'is_in_stock' => 1,
		  'weight' => 12,
		  'visibility' => 4,
		  'price_type' => 0,
		  'price_view' => 0,
		  'status' => 1,
		  'created_at' => strtotime('now'),
		  'category_ids' => $cats,
		  'store_id' => $storeID,
		  'tax_class_id' => 0,
		  'website_ids' => $websiteIDs
		);

		$productCheck->setData($p);
							$productCheck->setStockData(array(
								'is_in_stock' => 1,
								'qty' => $importData['qty']
							));		
		//$product->setIsInStock(true);
		/**
		 * Section of Bundle Options
		 * 
		 * Required Properties of Bundle Options are:-
		 * 1. title
		 * 2. option_id
		 * 3. delete
		 * 4. type
		 * 5. required
		 * 6. position
		 * 7. default_title
		 */
		/* $optionRawData = array();
		$optionRawData[0] = array(
		  'required' => 1,
		  'option_id' => '',
		  'position' => 0,
		  'type' => 'select',
		  'title' => 'FooOption',
		  'default_title' => 'FooOption',
		  'delete' => '',
		);

		/**
		 * Section of Bundle Selections
		 * 
		 * Required Properties of Bundle Selections
		 * 1.   selection_id
		 * 2.   option_id
		 * 3.   product_id
		 * 4.   delete
		 * 5.   selection_price_value
		 * 6.   selection_price_type
		 * 7.   selection_qty
		 * 8.   selection_can_change_qty
		 * 9.   position
		 * 10.  is_default
		 */
		/* $selectionRawData = array();
		$selectionRawData[0] = array();
		$selectionRawData[0][] = array(
		  'product_id' => 3,
		  'selection_qty' => 1,
		  'selection_can_change_qty' => 1,
		  'position' => 0,
		  'is_default' => 1,
		  'selection_id' => '',
		  'selection_price_type' => 0,
		  'selection_price_value' => 0.0,
		  'option_id' => '',
		  'delete' => ''
		);

		$productCheck->setBundleOptionsData($optionRawData);
		$productCheck->setBundleSelectionsData($selectionRawData);
		$productCheck->setCanSaveBundleSelections(true);
		$productCheck->setAffectBundleProductSelections(true);

		$productCheck->save(); */
//testcode ends here
					
            //##################
           // Now this will create the bundled product, but as we know we need to create a simple with the same name.
           if($importData['type']	== 'bundle'){
				if(isset($importData['bundle_attributes'])){

					require_once($magentoPath . 'includes/config.php');
					require_once($magentoPath . 'app/Mage.php');
					Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
					$optionCounter					=	0;
					$attributeCounter				=	0;
					$productCounter					=	0;
					$optionRawData					=	array(); 	//it will hold the different attributes values.
					$selectionRawData				=	array();	//Data regarding particular attribute.
					$attributeArr					=	explode(']',$importData['bundle_attributes']);
					//Now process each attribute one by one
					foreach($attributeArr as $singleAttribute){
						//Now we got data as [type|title|is_required. 1stly we have to remove [
						$singleAttributeData			=	str_replace('[','',$singleAttribute);
						//Now we got data as type|title|is_required. Now we will split it from |
						$singleAttributeValues					=	explode('|',$singleAttributeData);
						$isRequiredVal							=	strtolower(trim($singleAttributeValues[2]))=='yes'?1:0;
						$optionRawData[$optionCounter] 		= array(
																  'required' 			=> $isRequiredVal,
																  'option_id' 			=> '',
																  'position' 			=> 0,
																  'type' 				=> $singleAttributeValues[0],
																  'title' 				=> $singleAttributeValues[1],
																  'default_title' 		=> $singleAttributeValues[1],
																  'delete' 				=> '',
																);
						$optionCounter++;
					}
					foreach ($optionRawData as $key => $link)
					{
						if ($optionRawData[$key]['title'] == '')
						{

							unset($optionRawData[$key]);
						}
					}					
					$optionRawData[$optionCounter] 		= array(
										  'required' 			=> 1,
										  'option_id' 			=> '',
										  'position' 			=> 0,
										  'type' 				=> 'checkbox',
										  'title' 				=> 'Base Item',
										  'default_title' 		=> 'Base Item',
										  'delete' 				=> '',
										);

					if(isset($importData['bundle_attribute_products'])){
						$productArray				=	explode(']',$importData['bundle_attribute_products']);
						//We will create a base product 1stly
						if(!$productId = $product->getIdBySku($importData['sku'].'_base')){
							//$product = Mage::getModel('catalog/product');
							$productBase = new Mage_Catalog_Model_Product();
							// Build the product
							$productBase->setSku($importData['sku'].'_base');
							$productBase->setAttributeSetId('4'); // 4 for default attribute set. This is hardcoded
							$productBase->setTypeId('simple');
							$productBase->setName($importData['name']);
							$productBase->setCategoryIds(array()); # some cat id's, my is 7
							$productBase->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
							$productBase->setDescription($importData['description']);
							$productBase->setShortDescription($importData['short_description']);
							$productBase->setPrice($importData['price']); # Set some price
							if(isset($groupPricingData)):
								$productBase->setData('group_price',$groupPricingData);	
							endif;
						  if( !empty($importData['length'])){
								$productBase->setLength();
							}
							if( !empty($importData['width'])){
								$productBase->setWidth();
							} 
							if( !empty($importData['height'])){
								$productBase->setHeight();
							} 
							if( !empty($importData['description_specs'])){
								$productBase->setDescriptionSpecs();
							} 
							if( !empty($importData['description_related'])){
								$productBase->setDescriptionRelated();
							} 
							if( !empty($importData['description_related'])){
								$productBase->setDescriptionRelated();
							}
							if( !empty($importData['description_addon_title'])){
								$productBase->setDescriptionAddonTitle();
							}		
							# Custom created and assigned attributes
							//$productBase->setHeight('my_custom_attribute1_val');
							//$productBase->setWidth('my_custom_attribute2_val');
							//$productBase->setDepth('my_custom_attribute3_val');
							//$productBase->setType('my_custom_attribute4_val');
							//Default Magento attribute
							$productBase->setWeight($importData['weight']);
							$productBase->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
							$productBase->setStatus(1);
							if($importData['tax_class_id']=='none'){
								$taxClassId		=	0;
							}
							elseif($importData['tax_class_id']=='Taxable Goods'){
								$taxClassId		=	2;
							}
							elseif($importData['tax_class_id']=='Shipping'){
								$taxClassId		=	4;
							}
							else{
								$taxClassId		=	0;
							}
							$productBase->setTaxClassId($taxClassId); # My default tax class
							$productBase->setStockData(array(
								'is_in_stock' => 1,
								'qty' => $importData['qty']
							));
							$productBase->setCreatedAt(strtotime('now'));
							try {
								$productBase->save();
							}
							catch (Exception $ex) {
								//Handle the error
							}
						}						
						//Base product creation code ends here					
						//Now we will process the products associated with an attribute.
						//This loop will process product set of on attribute example: ['LT121|0',LT121|0]
						foreach ($productArray as $key => $link)
						{
							if ($productArray[$key] == '')
							{
								unset($productArray[$key]);
							}
						}

						foreach($productArray as $singleAttributeSet){
							$singleAttributeSetData				=	str_replace('[','',$singleAttributeSet);
							//Now we got all products of a single attribute for one loop sku|quantity|can_change_qty|position|is_default|price_type|price,sku|quantity|can_change_qty|position|is_default|price_type|price
							$singleAttributeSetValues			=	explode(',',$singleAttributeSetData);
							foreach($singleAttributeSetValues	as	$singleProduct){
								//got single product seprated by |.sku|price
								$singleProductVal				=	explode('|',$singleProduct);
								/*$selectionRawData[$attributeCounter][$productCounter] 	= array(
																							  'product_id' => $product -> getIdBySku(trim($singleProductVal['0'])),
																							  'selection_qty' => trim($singleProductVal['1']),
																							  'selection_can_change_qty' => strtolower(trim($singleProductVal['2']))=='yes'?1:0,
																							  'position' => trim($singleProductVal['3']),
																							  'is_default' => trim($singleProductVal['4']),
																							  'selection_id' => '',
																							  'selection_price_type' => trim($singleProductVal['5']),
																							  'selection_price_value' => trim($singleProductVal['6']),
																							  'option_id' => '',
																							  'delete' => ''
																							); */
									
								 $selectionRawData[$attributeCounter][$productCounter] 	= array(
																							  'product_id' => $product -> getIdBySku(trim($singleProductVal['0'])),
																							  'selection_qty' => 1,
																							  'selection_can_change_qty' => 0,
																							  'position' => $productCounter,
																							  'is_default' => 0,
																							  'selection_id' => '',
																							  'selection_price_type' => 0,
																							  'selection_price_value' => trim($singleProductVal['1']),
																							  'option_id' => '',
																							  'delete' => ''
																							);															
								$productCounter++;
								
							}
							$attributeCounter++;						
						}
						$selectionRawData[$attributeCounter+1][$productCounter] 	= array(
															  'product_id' => $product -> getIdBySku($importData['sku'].'_base'),
															  'selection_qty' => 1,
															  'selection_can_change_qty' => 0,
															  'position' => 0,
															  'is_default' => 1,
															  'selection_id' => '',
															  'selection_price_type' => 0,
															  'option_id' => '',
															  'delete' => ''
															);	
					}
						$p = array(
						  'type_id' => 'bundle',
						  'attribute_set_id' => 4,
						  'price_type' => 0,
						  'price_view' => 0
						);
						$product->setData($p);
						$product->setStockData(array(
							'is_in_stock' => 1,
							'qty' => $importData['qty']
						));

					 //Mage::register('product', $product);
					$product->setBundleOptionsData($optionRawData);
					$product->setBundleSelectionsData($selectionRawData);
					$product->setCanSaveBundleSelections(true);
					$product->setAffectBundleProductSelections(true);

				}
			} 
        if ( isset( $importData['crosssell'] ) ) {
            $linkIds = $this -> skusToIds( $importData['crosssell'], $product );
            if ( !empty( $linkIds ) ) {
                $product -> setCrossSellLinkData( $linkIds );
                } 
            } 
        
        if ( isset( $importData['associated'] ) ) {
            $linkIds = $this -> skusToIds( $importData['associated'], $product );
            if ( !empty( $linkIds ) ) {
                $product -> setGroupedLinkData( $linkIds );
                } 
            }

             
        
        if ( isset( $importData['category_ids'] ) ) {
            $product -> setCategoryIds( $importData['category_ids'] );
            } 
       /* if(
            isset($importData['tier_prices'])
            && !empty($importData['tier_prices'])
        ){
            $this->_editTierPrices($product, $importData['tier_prices']);
        } */
        
        if(
            isset($importData['Normal_Quantity_1'])
            && !empty($importData['Normal_Quantity_1'])
            && isset($importData['Normal_Price_1'])
            && !empty($importData['Normal_Price_1'])
            ){
				$columnName			=	'Normal_Price_';
				$count				=	1;
				$cloneTier			=	array();
				while(!empty($importData[$columnName.$count])){
					$calculatedString		=	$importData['Normal_Quantity_'.$count].'='.$importData[$columnName.$count];
					if($cloneTier['tier_prices']==''){
						$cloneTier['tier_prices']	=	$calculatedString;
					}
					else{
						$cloneTier['tier_prices']		=	$cloneTier['tier_prices'].'|'.$calculatedString;
					}
					$count++;
				}
				$this->_editTierPrices($product, $cloneTier['tier_prices']);
			}
            
        
        
        if ( isset( $importData['categories'] ) ) {
            
            if ( isset( $importData['store'] ) ) {
                $cat_store = $this -> _stores[$importData['store']];
                } else {
                $message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "store" for new products not defined', $field );
                Mage :: throwException( $message );
                } 
            
            $categoryIds = $this -> _addCategories( $importData['categories'], $cat_store );
            if ( $categoryIds ) {
                $product -> setCategoryIds( $categoryIds );
                } 
            
            } 
        
        foreach ( $this -> _ignoreFields as $field ) {
            if ( isset( $importData[$field] ) ) {
                unset( $importData[$field] );
                } 
            } 
        
        if ( $store -> getId() != 0 ) {
            $websiteIds = $product -> getWebsiteIds();
            if ( !is_array( $websiteIds ) ) {
                $websiteIds = array();
                } 
            if ( !in_array( $store -> getWebsiteId(), $websiteIds ) ) {
                $websiteIds[] = $store -> getWebsiteId();
                } 
            $product -> setWebsiteIds( $websiteIds );
            } 
        
        if ( isset( $importData['websites'] ) ) {
            $websiteIds = $product -> getWebsiteIds();
            if ( !is_array( $websiteIds ) ) {
                $websiteIds = array();
                } 
            $websiteCodes = split( ',', $importData['websites'] );
            foreach ( $websiteCodes as $websiteCode ) {
                try {
                    $website = Mage :: app() -> getWebsite( trim( $websiteCode ) );
                    if ( !in_array( $website -> getId(), $websiteIds ) ) {
                        $websiteIds[] = $website -> getId();
                        } 
                    } 
                catch ( Exception $e ) {
                    } 
                } 
            $product -> setWebsiteIds( $websiteIds );
            unset( $websiteIds );
            } 
        $custom_options = array(); 
        foreach ( $importData as $field => $value ) {
			if ( in_array( $field, $this -> _inventoryFields ) ) {
                continue;
                } 
            if ( in_array( $field, $this -> _imageFields ) ) {
                continue;
                } 
            
            $attribute = $this -> getAttribute( $field );
            if ( !$attribute ) {
				 /* CUSTOM OPTION CODE */
				if(strpos($field,':')!==FALSE && strlen($value)) {
				$values=explode('|',$value);
				if(count($values)>0) {
				@list($title,$type,$is_required,$sort_order) = explode(':',$field);
				$title = ucfirst(str_replace('_',' ',$title));
				$custom_options[] = array(
				'is_delete'=>0,
				'title'=>$title,
				'previous_group'=>'',
				'price_type'=>'',
				'type'=>$type,
				'is_require'=>$is_required,
				'sort_order'=>$sort_order,
				'values'=>array()
				);
				foreach($values as $v) {
				$parts = explode(':',$v);
				$title = $parts[0];
				if(count($parts)>1) {
				$price_type = $parts[1];
				} else {
				$price_type = 'fixed';
				}
				if(count($parts)>2) {
				$price = $parts[2];
				} else {
				$price =0;
				}
				if(count($parts)>3) {
				$sku = $parts[3];
				} else {
				$sku="";
				}
				if(count($parts)>4) {
				$sort_order = $parts[4];
				} else {
				$sort_order = 0;
				}
				switch($type) {
				case 'file':
				/* TODO */
				$custom_options[count($custom_options) - 1]['sku'] = $your_custom_sku;
				$custom_options[count($custom_options) - 1]['file_extension'] = $your_custom_file_extension;
				$custom_options[count($custom_options) - 1]['image_size_x'] = $your_custom_X_size;
				$custom_options[count($custom_options) - 1]['image_size_y'] = $your_custom_Y_size;
				$custom_options[count($custom_options) - 1]['price'] = $your_custom_price;
				$custom_options[count($custom_options) - 1]['price_type'] = $your_custom_price_type;
				break;

				case 'field':
				case 'area':
				$custom_options[count($custom_options) - 1]['max_characters'] = $sort_order;
				/* NO BREAK */

				case 'date':
				case 'date_time':
				case 'time':
				$custom_options[count($custom_options) - 1]['price_type'] = $price_type;
				$custom_options[count($custom_options) - 1]['price'] = $price;
				$custom_options[count($custom_options) - 1]['sku'] = $sku;
				break;

				case 'drop_down':
				case 'radio':
				case 'checkbox':
				case 'multiple':
				default:
				$custom_options[count($custom_options) - 1]['values'][]=array(
				'is_delete'=>0,
				'title'=>$title,
				'option_type_id'=>-1,
				'price_type'=>$price_type,
				'price'=>$price,
				'sku'=>$sku,
				'sort_order'=>$sort_order
				);
				break;
				}

				}
				}
				}
				/* END CUSTOM OPTION CODE */
                continue;
                } 

            $isArray = false;
            $setValue = $value;

            if ( $attribute -> getFrontendInput() == 'multiselect' ) {
                $value = split( self :: MULTI_DELIMITER, $value );
                $isArray = true;
                $setValue = array();
                } 
            
            if ( $value && $attribute -> getBackendType() == 'decimal' ) {
                $setValue = $this -> getNumber( $value );
                } 
            
            if ( $attribute -> usesSource() ) {
                $options = $attribute -> getSource() -> getAllOptions( false );
                
                if ( $isArray ) {
                    foreach ( $options as $item ) {
                        if ( in_array( $item['label'], $value ) ) {
                            $setValue[] = $item['value'];
                            } 
                        } 
                    } 
                else {
                    $setValue = null;
                    foreach ( $options as $item ) {
                        if ( $item['label'] == $value ) {
                            $setValue = $item['value'];
                            } 
                        } 
                    } 
                } 
            
            $product -> setData( $field, $setValue );
            } 
        
        if ( !$product -> getVisibility() ) {
            $product -> setVisibility( Mage_Catalog_Model_Product_Visibility :: VISIBILITY_NOT_VISIBLE );
            } 
                
        
        $stockData = array();
        //$inventoryFields = $product -> getTypeId() == 'simple' ? $this -> _inventorySimpleFields : $this -> _inventoryOtherFields;
        $inventoryFields = isset($this->_inventoryFieldsProductTypes[$product->getTypeId()]) ? $this->_inventoryFieldsProductTypes[$product->getTypeId()] : array();
		foreach ( $inventoryFields as $field ) {
            if ( isset( $importData[$field] ) ) {
                if ( in_array( $field, $this -> _toNumber ) ) {
                    $stockData[$field] = $this -> getNumber( $importData[$field] );
                    } 
                else {
                    $stockData[$field] = $importData[$field];
                    } 
                } 
            } 
        $product -> setStockData( $stockData );

        $imageData = array();
        foreach ( $this -> _imageFields as $field ) {
            if ( !empty( $importData[$field] ) && $importData[$field] != 'no_selection' ) {
                if ( !isset( $imageData[$importData[$field]] ) ) {
                    $imageData[$importData[$field]] = array();
                    } 
                $imageData[$importData[$field]][] = $field;
                } 
            } 
        
        foreach ( $imageData as $file => $fields ) {
            try {
                $product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import/' . $file, $fields, false );
                } 
            catch ( Exception $e ) {
                } 
            } 
        
        if ( !empty( $importData['gallery'] ) ) {
            $galleryData = explode( ',', $importData["gallery"] );
            foreach( $galleryData as $gallery_img ) {
                try {
					//echo Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img;
                    $product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img, null, false, false );
                    } 
                catch ( Exception $e ) {
                    } 
                } 
            }

            
            
            
            
             
            if ( !empty( $importData['small_image'] ) ) {
            $galleryData = explode( ',', $importData["small_image"] );
            foreach( $galleryData as $gallery_img ) {
                try {
					//echo Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img;
                    $product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img, 'small_image', false, false );
                    } 
                catch ( Exception $e ) {
                    } 
                } 
            }
            if ( !empty( $importData['thumbnail'] ) ) {
            $galleryData = explode( ',', $importData["thumbnail"] );
            foreach( $galleryData as $gallery_img ) {
                try {
					//echo Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img;
                    $product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img, 'thumbnail', false, false );
                    } 
                catch ( Exception $e ) {
                    } 
                } 
            }                 
            if ( !empty( $importData['image'] ) ) {
            $galleryData = explode( ',', $importData["image"] );
            foreach( $galleryData as $gallery_img ) {
                try {
					//echo Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img;
                    $product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img, 'image', false, false );
                    } 
                catch ( Exception $e ) {
                    } 
                } 
            } 

		$product->setHasOptions(1);	
		$product -> setIsMassupdate( true );        
        $product -> setExcludeUrlRewrite( true );	     


		$product->save();
		
			     
        /* Remove existing custom options attached to the product */
		foreach ($product->getOptions() as $o) {
			$o->getValueInstance()->deleteValue($o->getId());
			$o->deletePrices($o->getId());
			$o->deleteTitles($o->getId());
			$o->delete();
		}
		/* Add the custom options specified in the CSV import file */
		if(count($custom_options)) {
			foreach($custom_options as $option) {
				try {$opt = Mage::getModel('catalog/product_option');
					$opt->setProduct($product);
					$opt->addOption($option);
					$opt->saveOptions();
				}
				catch (Exception $e) {}
			}
		}
        return true;
       
    } 
    
    protected function userCSVDataAsArray( $data )
    
    {
        return explode( ',', str_replace( " ", "", $data ) );
        } 
    
    protected function skusToIds( $userData, $product )
    
    {
        $productIds = array();
        foreach ( $this -> userCSVDataAsArray( $userData ) as $oneSku ) {
            if ( ( $a_sku = ( int )$product -> getIdBySku( $oneSku ) ) > 0 ) {
                parse_str( "position=", $productIds[$a_sku] );
                } 
            } 
        return $productIds;
        } 
    
    protected $_categoryCache = array();
    
    protected function _addCategories( $categories, $store )
    
    {
        // $rootId = $store->getRootCategoryId();
        // $rootId = Mage::app()->getStore()->getRootCategoryId();
        $rootId = 2; // our store's root category id
        if ( !$rootId ) {
            return array();
            } 
        $rootPath = '1/' . $rootId;
        if ( empty( $this -> _categoryCache[$store -> getId()] ) ) {
            $collection = Mage :: getModel( 'catalog/category' ) -> getCollection()
             -> setStore( $store )
             -> addAttributeToSelect( 'name' );
            $collection -> getSelect() -> where( "path like '" . $rootPath . "/%'" );
            
            foreach ( $collection as $cat ) {
                try {
                    $pathArr = explode( '/', $cat -> getPath() );
                    $namePath = '';
                    for ( $i = 2, $l = sizeof( $pathArr ); $i < $l; $i++ ) {
                        $name = $collection -> getItemById( $pathArr[$i] ) -> getName();
                        $namePath .= ( empty( $namePath ) ? '' : '/' ) . trim( $name );
                        } 
                    $cat -> setNamePath( $namePath );
                    } 
                catch ( Exception $e ) {
                    echo "ERROR: Cat - ";
                    print_r( $cat );
                    continue;
                    } 
                } 
            
            $cache = array();
            foreach ( $collection as $cat ) {
                $cache[strtolower( $cat -> getNamePath() )] = $cat;
                $cat -> unsNamePath();
                } 
            $this -> _categoryCache[$store -> getId()] = $cache;
            } 
        $cache = &$this -> _categoryCache[$store -> getId()];
        
        $catIds = array();
        foreach ( explode( ',', $categories ) as $categoryPathStr ) {
            $categoryPathStr = preg_replace( '#s*/s*#', '/', trim( $categoryPathStr ) );
            if ( !empty( $cache[$categoryPathStr] ) ) {
                $catIds[] = $cache[$categoryPathStr] -> getId();
                continue;
                } 
            $path = $rootPath;
            $namePath = '';
            foreach ( explode( '/', $categoryPathStr ) as $catName ) {
                $namePath .= ( empty( $namePath ) ? '' : '/' ) . strtolower( $catName );
                if ( empty( $cache[$namePath] ) ) {
                    $cat = Mage :: getModel( 'catalog/category' )
                     -> setStoreId( $store -> getId() )
                     -> setPath( $path )
                     -> setName( $catName )
                     -> setIsActive( 1 )
                     -> save();
                    $cache[$namePath] = $cat;
                    } 
                $catId = $cache[$namePath] -> getId();
                $path .= '/' . $catId;
                } 
            if ( $catId ) {
                $catIds[] = $catId;
                } 
            } 
        return join( ',', $catIds );
        } 
    
    protected function _removeFile( $file )
    
    {
        if ( file_exists( $file ) ) {
            if ( unlink( $file ) ) {
                return true;
                } 
            } 
        return false;
        } 
    }
    
