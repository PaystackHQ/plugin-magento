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

class Paystack_Inline_WebhookController extends Mage_Core_Controller_Front_Action
{
   /**
     * Instantiate IPN model and pass IPN request to it
     */
    public function handlerAction()
    {
        if (!$this->getRequest()->isPost() || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER)) {
            return;
        }

        try {
            $input = file_get_contents('php://input');
            $secret_key = $transactionStatus = Mage::helper('paystack_inline')->getSecretKey();
            if(!$_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] || ($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, $secret_key))){
                // silently forget this ever happened
                return;
            }

            $event = json_decode($input);
            // we only use charge.success
            if('charge.success' == $event->event){
                // get order ID (it's always the first after exploding transaction reference)
                $orderId = explode("-", $event->data->reference, 2)[0];
                // load Transaction
                $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
                if(!$order){
                    return;
                }
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Payment Success.');
                $order->save();
            }
        } catch (Exception $e) {
            Mage::logException($e);
            print_r($e->getMessage());
            $this->getResponse()->setHttpResponseCode(200);
        }
    }

}
