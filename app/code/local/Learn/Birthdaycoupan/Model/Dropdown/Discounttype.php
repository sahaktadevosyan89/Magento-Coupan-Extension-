<?php
class Learn_Birthdaycoupan_Model_Dropdown_Discounttype
{
	public function toOptionArray()
	{
		return array(
			array(
				'value' => Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION ,
				'label' => Mage::helper('salesrule')->__('Percent of product price discount'),
			),
			array(
				'value' => Mage_SalesRule_Model_Rule::BY_FIXED_ACTION,
				'label' => Mage::helper('salesrule')->__('Fixed amount discount'),
			),
			array(
				'value' => Mage_SalesRule_Model_Rule::CART_FIXED_ACTION,
				'label' => Mage::helper('salesrule')->__('Fixed amount discount for whole cart'),
			),
			/* 
			array(
				'value' => Mage_SalesRule_Model_Rule::BUY_X_GET_Y_ACTION,
				'label' => Mage::helper('salesrule')->__('Buy X get Y free (discount amount is Y)'),
			),
			*/	
		);
	}
}