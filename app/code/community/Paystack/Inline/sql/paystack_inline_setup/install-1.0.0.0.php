<?php
/**
 * Paytsack Inline Extension
 *
 * DISCLAIMER
 * This file will not be supported if it is modified.
 *
 * @category   Paystack
 * @author     Ibrahim Lawal (@ibrahimlawal)
 * @package    Paystack_Inline
 * @copyright  Copyright (c) 2016 Paystack. (https://www.paystack.com/)
 * @license    https://raw.githubusercontent.com/PaystackHQ/paystack-magento/master/LICENSE   MIT License (MIT)
 */
$installer = $this;
// $installer->startSetup();
// $installer->run("
// ALTER TABLE `{$installer->getTable('sales/quote_payment')}`
// ADD `custom_field_one` VARCHAR( 255 ) NOT NULL,
// ADD `custom_field_two` VARCHAR( 255 ) NOT NULL;
//
// ALTER TABLE `{$installer->getTable('sales/order_payment')}`
// ADD `custom_field_one` VARCHAR( 255 ) NOT NULL,
// ADD `custom_field_two` VARCHAR( 255 ) NOT NULL;
// ");
// $installer->endSetup();

$helper = Mage::helper('paystack_inline');
// if ($isUpdate) {
//     $text = 'The Paystack Inline Extension has been successfully upgraded. New fields are ready to be configured.';
// } else {

$text = 'The Paystack Inline Extension has been successfully installed and is ready to be configured.';

Mage::getModel('adminnotification/inbox')->addMajor(
    $helper->__($text),
    $helper->__($text),
    '',
    true
);
