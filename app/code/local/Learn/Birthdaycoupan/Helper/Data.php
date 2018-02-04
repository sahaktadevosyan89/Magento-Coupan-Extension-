<?php
class Learn_Birthdaycoupan_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_BC_ACTIVE		= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_active';
    const XML_PATH_BC_SENDER		= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_email_sender';
    const XML_PATH_BC_BEFORE		= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_sendemail_before_birthday';
    const XML_PATH_BC_DIS_TYPE		= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_discount_type';
    const XML_PATH_BC_DIS_AMOUNT	= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_discount_amount';
    const XML_PATH_BC_EXPIRES		= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_expires_in';
    const XML_PATH_BC_PER_CUSTOMER	= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_uses_per_customer';
    const XML_PATH_BC_PER_COUPAN	= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_uses_per_coupan';
    const XML_PATH_BC_CUSTOMER_GRP	= 'birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_customer_group';
	
	
	public function conf($code, $store = null) {
        return Mage::getStoreConfig($code, $store);
    }
	/*- Enable -*/
	public function bc_active($store) {
		return $this->conf(self::XML_PATH_BC_ACTIVE, $store);
	}
	/*- Admin Sender -*/
	public function bc_admin_sender($store) {
		return $this->conf(self::XML_PATH_BC_SENDER, $store);
	}	
	/*- Before Date-*/
	public function getBeforeEmailDays($store) {
		return $this->conf(self::XML_PATH_BC_BEFORE, $store);
	}
	/*- Discount Type -*/
	public function bc_discount_type($store) {
		return $this->conf(self::XML_PATH_BC_DIS_TYPE, $store);
	}
	/*- Discount Amount -*/
	public function bc_discount_amount($store) {
		return $this->conf(self::XML_PATH_BC_DIS_AMOUNT, $store);
	}
	/*- EXPIRES IN -*/
	public function bc_expires($store) {
		return $this->conf(self::XML_PATH_BC_EXPIRES, $store);
	}
	/*- Per User -*/
	public function bc_per_user($store) {
		return $this->conf(self::XML_PATH_BC_PER_CUSTOMER, $store);
	}
	/*- Per Coupan -*/
	public function bc_per_coupan($store) {
		return $this->conf(self::XML_PATH_BC_PER_COUPAN, $store);
	}
	/*- Customer Group-*/
	public function bc_customer_group($store) {
		return $this->conf(self::XML_PATH_BC_CUSTOMER_GRP, $store);
	}
	
	public function admin_notify_emailId() {
		$admin = Mage::getStoreConfig('birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_email_sender');     
		return Mage::getStoreConfig('trans_email/ident_'.$admin.'/email', $store);
	}
	
	public function admin_notify_name() {
		$admin = Mage::getStoreConfig('birthdaycoupan_tab/birthdaycoupan_setting/birthdaycoupan_email_sender');     
		return Mage::getStoreConfig('trans_email/ident_'.$admin.'/name', $store);
	}
	
	public function send_birthday_coupan($data, $customer_email_id, $customer_name) 
	{
		$bc_template  = Mage::getModel('core/email_template')->loadDefault('birthdaycoupan_template');
		$notify_template = $bc_template->getProcessedTemplate($data);
		$adminSalesRepEmail = $this->admin_notify_emailId();
		$adminSalesRepName = $this->admin_notify_name();
		
		$subject = $this->__("Reg : Birthday Coupan");
		
		// Send the Mail to Customer
		$mail = Mage::getModel('core/email')
			->setToName($customer_name)
			->setToEmail($customer_email_id)
			->setBody($notify_template)
			->setSubject($subject)
			->setFromEmail($adminSalesRepEmail)
			->setFromName($adminSalesRepName)
			->setType('html');
		try {
			$mail->send();
		}
		catch(Exception $error) {
			Mage::getSingleton('core/session')->addError($error->getMessage());
			//echo $error->getMessage();
		}
	}
	
}