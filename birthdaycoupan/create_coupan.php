<?php 
/* require_once('./../app/Mage.php');  */
require_once('../app/Mage.php');
umask(0);
Mage::app();
$bc_helper = Mage::helper('birthdaycoupan');
if($bc_helper->bc_active()) {
	
	$admin_sender = $bc_helper->bc_admin_sender();	
	
	$getBeforeDays = $bc_helper->getBeforeEmailDays();	
	$discount_type   = $bc_helper->bc_discount_type();
	$discount_amount = $bc_helper->bc_discount_amount();
	$per_user		 = $bc_helper->bc_per_user();
	$per_coupan		 = $bc_helper->bc_per_coupan();
	$expires_in		 = $bc_helper->bc_expires();
	$customer_group  = $bc_helper->bc_customer_group();

	$getBeforeDays = ($getBeforeDays < 0 ) ? 0 : $getBeforeDays;	
	$current_search = date('m-d',  strtotime("+$getBeforeDays day"));
	$collection = Mage::getResourceModel('customer/customer_collection')->addNameToSelect();
	$collection->joinAttribute('dob','customer/dob', 'entity_id');
	$collection->addAttributeToFilter('dob', array(  array('like' => '%_____'. $current_search.'%') ));
	$collection->addAttributeToFilter('group_id', array('in' =>  array(explode(",", $customer_group))));
	/* echo $collection->getSelect(); */
	$customers = $collection->getData();
	
	/* 
	print_r($customers);
	exit;
	*/	
	$from_date = date('Y-m-d 00:00:00');
	$expir_date = ($expires_in + $getBeforeDays) + 1;
	$to_date = date('Y-m-d 23:59:59',  strtotime("+$expir_date day"));
	
    foreach($customers as $key => $customer) {
		$customer_id = $customer['entity_id'];
		$customer_name = $customer['firstname']." ".$customer['lastname'];
		$coupan_code = Mage::helper('core')->getRandomString(16);
		$data = array(
			'name' => "Birthday_".$customer_id,
			'description' => null,
			'is_active' => 1,
			'website_ids' => array(1),
			'customer_group_ids' => explode(",", $customer_group),
			'coupon_type' => 2,
			/* 'coupon_code' => date('Y').'birthday'.$customer_id, */
			'coupon_code' => $coupan_code,
			'uses_per_coupon' => $per_coupan,
			'uses_per_customer' => $per_user,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'sort_order' => null,
			'is_rss' => 1,
			'simple_action' => $discount_type,
			'discount_amount' => $discount_amount,
			'discount_qty' => 0,
			'discount_step' => null,
			'apply_to_shipping' => 0,
			'simple_free_shipping' => 0,
			'stop_rules_processing' => 0,
			'store_labels' => array('Birthday Discount')
		);
	 
		$model = Mage::getModel('salesrule/rule');
		/* $data = Mage_Core_Controller_Varien_Action::_filterDates($data, array('from_date', 'to_date'));  */
		$validateResult = $model->validateData(new Varien_Object($data)); 
		if ($validateResult == true) { 
			$model->loadPost($data);
			$model->save();
		}
		
		$mail_data = array(
			"coupon_code" => $coupan_code,
			"customer_name" => $customer_name,			
			"from_date" => $from_date,			
			"to_date" => $to_date,			
		);
		$mail = $bc_helper->send_birthday_coupan($mail_data, $customer['email'], $customer_name);
		echo "Coupan Code - ". $coupan_code. "<br />" . " Name : $customer_name <br/><br/>";
	}
} else { 
	echo "Disabled";
}



?>
