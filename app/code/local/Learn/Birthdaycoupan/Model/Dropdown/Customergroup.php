<?php
class Learn_Birthdaycoupan_Model_Dropdown_Customergroup
{
	public function toOptionArray()
	{
		$group = Mage::getModel('customer/group')->getCollection();
		$groupArray = array();
		foreach ($group as $eachGroup) {
			$groupData = array(
				'value' => $eachGroup->getCustomerGroupId(),
				'label' => $eachGroup->getCustomerGroupCode(),
				/* 
                    'customer_group_id' => $eachGroup->getCustomerGroupId(),
                    'customer_group_code' => $eachGroup->getCustomerGroupCode(),
                    'tax_class_id' => $eachGroup->getTaxClassId() // we dont required this 
				*/
			);
			if (!empty($groupData)) {
				array_push($groupArray, $groupData);
			}
		}
		return $groupArray;
	}
}