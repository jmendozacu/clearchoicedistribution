<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Customer_Edit_Tab_Account extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Initialize block
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize form
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Account
     */
    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_account');
        $form->setFieldNameSuffix('account');

        $customer = Mage::registry('current_customer');

        /** @var $customerForm Mage_Customer_Model_Form */
        $customerForm = Mage::getModel('customer/form');
        $customerForm->setEntity($customer)
            ->setFormCode('adminhtml_customer')
            ->initDefaultValues();

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('customer')->__('Account Information')
        ));

        $attributes = $customerForm->getAttributes();
        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            $attribute->setFrontendLabel(Mage::helper('customer')->__($attribute->getFrontend()->getLabel()));
            $attribute->unsIsVisible();
        }

        $disableAutoGroupChangeAttributeName = 'disable_auto_group_change';
        $this->_setFieldset($attributes, $fieldset, array($disableAutoGroupChangeAttributeName));

        $form->getElement('group_id')->setRenderer($this->getLayout()
            ->createBlock('adminhtml/customer_edit_renderer_attribute_group')
            ->setDisableAutoGroupChangeAttribute($customerForm->getAttribute($disableAutoGroupChangeAttributeName))
            ->setDisableAutoGroupChangeAttributeValue($customer->getData($disableAutoGroupChangeAttributeName)));

        if ($customer->getId()) {
            $form->getElement('website_id')->setDisabled('disabled');
            $form->getElement('created_in')->setDisabled('disabled');
        } else {
            $fieldset->removeField('created_in');
            $form->getElement('website_id')->addClass('validate-website-has-store');

            $websites = array();
            foreach (Mage::app()->getWebsites(true) as $website) {
                $websites[$website->getId()] = !is_null($website->getDefaultStore());
            }
            $prefix = $form->getHtmlIdPrefix();

            $form->getElement('website_id')->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                var {$prefix}_websites = " . Mage::helper('core')->jsonEncode($websites) .";
                Validation.add(
                    'validate-website-has-store',
                    '" . Mage::helper('customer')->__('Please select a website which contains store view') . "',
                    function(v, elem){
                        return {$prefix}_websites[elem.value] == true;
                    }
                );
                Element.observe('{$prefix}website_id', 'change', function(){
                    Validation.validate($('{$prefix}website_id'))
                }.bind($('{$prefix}website_id')));
                "
                . '</script>'
            );
        }
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $form->getElement('website_id')->setRenderer($renderer);

//        if (Mage::app()->isSingleStoreMode()) {
//            $fieldset->removeField('website_id');
//            $fieldset->addField('website_id', 'hidden', array(
//                'name'      => 'website_id'
//            ));
//            $customer->setWebsiteId(Mage::app()->getStore(true)->getWebsiteId());
//        }

        $customerStoreId = null;
        if ($customer->getId()) {
            $customerStoreId = Mage::app()->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
        }

        $prefixElement = $form->getElement('prefix');
        if ($prefixElement) {
            $prefixOptions = $this->helper('customer')->getNamePrefixOptions($customerStoreId);
            if (!empty($prefixOptions)) {
                $fieldset->removeField($prefixElement->getId());
                $prefixField = $fieldset->addField($prefixElement->getId(),
                    'select',
                    $prefixElement->getData(),
                    $form->getElement('group_id')->getId()
                );
                $prefixField->setValues($prefixOptions);
                if ($customer->getId()) {
                    $prefixField->addElementValues($customer->getPrefix());
                }

            }
        }

        $suffixElement = $form->getElement('suffix');
        if ($suffixElement) {
            $suffixOptions = $this->helper('customer')->getNameSuffixOptions($customerStoreId);
            if (!empty($suffixOptions)) {
                $fieldset->removeField($suffixElement->getId());
                $suffixField = $fieldset->addField($suffixElement->getId(),
                    'select',
                    $suffixElement->getData(),
                    $form->getElement('lastname')->getId()
                );
                $suffixField->setValues($suffixOptions);
                if ($customer->getId()) {
                    $suffixField->addElementValues($customer->getSuffix());
                }
            }
        }
         ///new code start here
         $fieldset->addField('businessname', 'text', array(
            'name'      => 'businessname',
             'label'=>'Business-Name'
            
            ));
         $fieldset->addField('businesswebsite', 'text', array(
            'name'      => 'businesswebsite',
             'label'=>'Business-Website'
            
            ));
         $fieldset->addField('typeofbusiness', 'text', array(
            'name'      => 'typeofbusiness',
             'label'=>'Type of Business'
            
            ));
         $fieldset->addField('dateestdmonth', 'text', array(
            'name'      => 'dateestdmonth',
             'label'=>'Month of Estd.'
            
            ));
         $fieldset->addField('dateestdyear', 'text', array(
            'name'      => 'dateestdyear',
             'label'=>'Year of Estd.'
            
            ));
         $fieldset->addField('businesslicensenumber', 'text', array(
            'name'      => 'businesslicensenumber',
             'label'=>'Business License Number.'
            
            ));
            $val		= array();
            $val		=	$customer->getData();
            $pdfUrl		=	$val['businesslicensecopy'];
			$fieldset->addField('businesslicensecopy', 'text', array(
				'name'      => 'businesslicensecopy',
				'label'=>'Business License Copy.',
				'after_element_html' => '<a href="'.$pdfUrl.'" target="_blank">Download Attachment</a>'           
            )); 
         $fieldset->addField('sellerpermitnumber', 'text', array(
            'name'      => 'sellerpermitnumber',
             'label'=>'Sellers Permit Number (CA only; resale certificate required)'
            
            ));
            $pdfUrl		=	$val['sellerpermitnumbercopy'];
			$fieldset->addField('sellerpermitnumbercopy', 'text', array(
				'name'      => 'sellerpermitnumbercopy',
				'label'=>'Seller Permit Copy.',
				'after_element_html' => '<a href="'.$pdfUrl.'" target="_blank">Download Attachment</a>'           
            )); 
          $fieldset->addField('ein', 'text', array(
            'name'      => 'ein',
             'label'=>'Federal Tax ID Number (EIN)'
            
            ));                                    
          $fieldset->addField('legalstatus', 'text', array(
            'name'      => 'legalstatus',
             'label'=>'Company Type/Legal Status'
            
            ));                                    
         ///new field end here
         
             /**
              * Business/Trade References-1 code starts here
              * */
            $newFieldset1 = $form->addFieldset(
                'references1',
                array('legend'=>Mage::helper('customer')->__('Business/Trade Reference - 1'))
            );                       
			$newFieldset1->addField('btrefrencebusinessname', 'text', array(
            'name'      => 'btrefrencebusinessname',
             'label'=>'Business Name'            
            ));
         
			$newFieldset1->addField('btrefrencecontactname', 'text', array(
            'name'      => 'btrefrencecontactname',
             'label'=>'Contact Name'            
            ));
         
			$newFieldset1->addField('btrefrencetelephone', 'text', array(
            'name'      => 'btrefrencetelephone',
             'label'=>'Telephone'            
            ));
         
			$newFieldset1->addField('btrefrencefax', 'text', array(
            'name'      => 'btrefrencefax',
             'label'=>'Fax Number'            
            ));
         
			$newFieldset1->addField('street_11', 'text', array(
            'name'      => 'street_11',
             'label'=>'Street Address'            
            ));
         
			$newFieldset1->addField('city1', 'text', array(
            'name'      => 'city1',
             'label'=>'City'            
            ));
         
			$newFieldset1->addField('region_id1', 'text', array(
            'name'      => 'region_id1',
             'label'=>'State/Province'            
            ));
         
			$newFieldset1->addField('region1', 'text', array(
            'name'      => 'region1',
             'label'=>'State/Province'            
            ));
         
			$newFieldset1->addField('zip1', 'text', array(
            'name'      => 'zip1',
             'label'=>'Zip'            
            ));
         
			$newFieldset1->addField('country1', 'text', array(
            'name'      => 'country1',
             'label'=>'Country'            
            ));
         
			$newFieldset1->addField('btrefrenceemail', 'text', array(
            'name'      => 'btrefrenceemail',
             'label'=>'Email'            
            ));
         
			$newFieldset1->addField('btrefrencebusinesswebsite', 'text', array(
            'name'      => 'btrefrencebusinesswebsite',
             'label'=>'Website'            
            ));
         
            
			/*** 		Business/Trade References-1 code Ends here **/
         
             /**
              * Business/Trade References-2 code starts here
              * */
            $newFieldset2 = $form->addFieldset(
                'references2',
                array('legend'=>Mage::helper('customer')->__('Business/Trade Reference - 2'))
            );                       
			$newFieldset2->addField('btrefrencebusinessname2', 'text', array(
            'name'      => 'btrefrencebusinessname2',
             'label'=>'Business Name'            
            ));
         
			$newFieldset2->addField('btrefrencecontactname2', 'text', array(
            'name'      => 'btrefrencecontactname2',
             'label'=>'Contact Name'            
            ));
         
			$newFieldset2->addField('btrefrencetelephone2', 'text', array(
            'name'      => 'btrefrencetelephone2',
             'label'=>'Telephone'            
            ));
         
			$newFieldset2->addField('btrefrencefax2', 'text', array(
            'name'      => 'btrefrencefax2',
             'label'=>'Fax Number'            
            ));
         
			$newFieldset2->addField('street_12', 'text', array(
            'name'      => 'street_12',
             'label'=>'Street Address'            
            ));
         
			$newFieldset2->addField('city2', 'text', array(
            'name'      => 'city2',
             'label'=>'City'            
            ));
         
			$newFieldset2->addField('region_id2', 'text', array(
            'name'      => 'region_id2',
             'label'=>'State/Province'            
            ));
         
			$newFieldset2->addField('region2', 'text', array(
            'name'      => 'region2',
             'label'=>'State/Province'            
            ));
         
			$newFieldset2->addField('zip2', 'text', array(
            'name'      => 'zip2',
             'label'=>'Zip'            
            ));
         
			$newFieldset2->addField('country2', 'text', array(
            'name'      => 'country2',
             'label'=>'Country'            
            ));
         
			$newFieldset2->addField('btrefrenceemail2', 'text', array(
            'name'      => 'btrefrenceemail2',
             'label'=>'Email'            
            ));
         
			$newFieldset2->addField('btrefrencebusinesswebsite2', 'text', array(
            'name'      => 'btrefrencebusinesswebsite2',
             'label'=>'Website'            
            ));
         
            
			/*** 		Business/Trade References-2 code Ends here **/
        if ($customer->getId()) {
            if (!$customer->isReadonly()) {
                // Add password management fieldset
                $newFieldset = $form->addFieldset(
                    'password_fieldset',
                    array('legend' => Mage::helper('customer')->__('Password Management'))
                );
                // New customer password
                $field = $newFieldset->addField('new_password', 'text',
                    array(
                        'label' => Mage::helper('customer')->__('New Password'),
                        'name'  => 'new_password',
                        'class' => 'validate-new-password'
                    )
                );
                $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

                // Prepare customer confirmation control (only for existing customers)
                $confirmationKey = $customer->getConfirmation();
                if ($confirmationKey || $customer->isConfirmationRequired()) {
                    $confirmationAttribute = $customer->getAttribute('confirmation');
                    if (!$confirmationKey) {
                        $confirmationKey = $customer->getRandomConfirmationKey();
                    }
                    $element = $fieldset->addField('confirmation', 'select', array(
                        'name'  => 'confirmation',
                        'label' => Mage::helper('customer')->__($confirmationAttribute->getFrontendLabel()),
                    ))->setEntityAttribute($confirmationAttribute)
                        ->setValues(array('' => 'Confirmed', $confirmationKey => 'Not confirmed'));

                    // Prepare send welcome email checkbox if customer is not confirmed
                    // no need to add it, if website ID is empty
                    if ($customer->getConfirmation() && $customer->getWebsiteId()) {
                        $fieldset->addField('sendemail', 'checkbox', array(
                            'name'  => 'sendemail',
                            'label' => Mage::helper('customer')->__('Send Welcome Email after Confirmation')
                        ));
                        $customer->setData('sendemail', '1');
                    }
                }
            }
        } else {
            $newFieldset = $form->addFieldset(
                'password_fieldset',
                array('legend'=>Mage::helper('customer')->__('Password Management'))
            );
            $field = $newFieldset->addField('password', 'text',
                array(
                    'label' => Mage::helper('customer')->__('Password'),
                    'class' => 'input-text required-entry validate-password',
                    'name'  => 'password',
                    'required' => true
                )
            );
            $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

            // Prepare send welcome email checkbox
            $fieldset->addField('sendemail', 'checkbox', array(
                'label' => Mage::helper('customer')->__('Send Welcome Email'),
                'name'  => 'sendemail',
                'id'    => 'sendemail',
            ));
            $customer->setData('sendemail', '1');
            if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('sendemail_store_id', 'select', array(
                    'label' => $this->helper('customer')->__('Send From'),
                    'name' => 'sendemail_store_id',
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
                ));
            }
        }

        // Make sendemail and sendmail_store_id disabled if website_id has empty value
        $isSingleMode = Mage::app()->isSingleStoreMode();
        $sendEmailId = $isSingleMode ? 'sendemail' : 'sendemail_store_id';
        $sendEmail = $form->getElement($sendEmailId);

        $prefix = $form->getHtmlIdPrefix();
        if ($sendEmail) {
            $_disableStoreField = '';
            if (!$isSingleMode) {
                $_disableStoreField = "$('{$prefix}sendemail_store_id').disabled=(''==this.value || '0'==this.value);";
            }
            $sendEmail->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                $('{$prefix}website_id').disableSendemail = function() {
                    $('{$prefix}sendemail').disabled = ('' == this.value || '0' == this.value);".
                    $_disableStoreField
                ."}.bind($('{$prefix}website_id'));
                Event.observe('{$prefix}website_id', 'change', $('{$prefix}website_id').disableSendemail);
                $('{$prefix}website_id').disableSendemail();
                "
                . '</script>'
            );
        }

        if ($customer->isReadonly()) {
            foreach ($customer->getAttributes() as $attribute) {
                $element = $form->getElement($attribute->getAttributeCode());
                if ($element) {
                    $element->setReadonly(true, true);
                }
            }
        }

        $form->setValues($customer->getData());
        $this->setForm($form);
        return $this;
    }

    /**
     * Return predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'      => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_file'),
            'image'     => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_image'),
            'boolean'   => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_boolean'),
        );
    }
}
