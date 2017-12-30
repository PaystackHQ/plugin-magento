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

class Paystack_Inline_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function cancelAction()
    {
        Mage::getSingleton('core/session')->addError(
            Mage::helper('paystack_inline')->__("Payment cancelled."));

        $session = Mage::getSingleton('checkout/session');
        if ($session->getLastRealOrderId())
        {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId())
            {
                //Cancel order
                if ($order->getState() != Mage_Sales_Model_Order::STATE_CANCELED)
                {
                    $order->registerCancellation("Canceled by User")->save();
                }
                $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
                //Return quote
                if ($quote->getId())
                {
                    $quote->setIsActive(1)
                        ->setReservedOrderId(NULL)
                        ->save();
                    $session->replaceQuote($quote);
                }

                //Unset data
                $session->unsLastRealOrderId();
            }
        }

        return $this->getResponse()->setRedirect( Mage::getUrl('checkout/onepage'));
    }

    public function popAction()
    {
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','paystack_inline',array('template' => 'paystack/pop.phtml'));
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    public function redirectAction()
    {
        $transaction = Mage::helper('paystack_inline')->initializeTransaction();
        if(!$transaction){
            return;
        }
        if($transaction->error)
        {
            Mage::getModel('adminnotification/inbox')->addMajor(
                Mage::helper('paystack_inline')->__("Error while attempting to initialize transaction for order: " . $transaction->orderId),
                Mage::helper('paystack_inline')->__($transaction->error),
                '',
                true
            );
        }
        else
        {
            $this->getResponse()->setRedirect($transaction->authorization_url);
        }
    }

    public function responseAction()
    {
        $success = false;

        $orderId = $this->getRequest()->get("orderId");
        $reference = $this->getRequest()->get("reference");

        // Both are required
        if(!$orderId || !$reference){
            return;
        }

        // reference must start with orderId by design
        if(strpos($reference, $orderId) !== 0){
            return;
        }

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        if(!$order){
            return;
        }


        // verify transaction with paystack
        $transactionStatus = Mage::helper('paystack_inline')->verifyTransaction($reference);
        if($transactionStatus->error)
        {
            Mage::getModel('adminnotification/inbox')->addMajor(
                Mage::helper('paystack_inline')->__("Error while attempting to verify transaction: reference: " . $reference),
                Mage::helper('paystack_inline')->__($transactionStatus->error),
                '',
                true
            );
        }
        elseif($transactionStatus->status == 'success')
        {
            // $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Payment Success.');
            // $order->setState($transactionStatus->order_status, true, 'Payment Success.');
            // $order->save();
            switch ($transactionStatus->order_status) {
                case 'complete':
                    $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
                    $order->save();
                break;
                case 'pending':
                   $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true, 'Payment Success.')->save();
                break;
                case 'payment_pa':
                    $order->setState(Mage_Sales_Model_Order::STATE_NEW, true)->save();
                break;
                case 'processing':
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Payment Success.')->save();
                break;
                case 'closed':
                    $order->setState(Mage_Sales_Model_Order::STATE_CLOSED, true, 'Payment Success.')->save();
                break;
                case 'canceled':
                    $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Payment Success.')->save();
                break;
                case 'holded':
                    $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true, 'Payment Success.')->save();
                break;
                default:
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Payment Success.')->save();
                    break;
            }
            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success');
            $order->sendNewOrderEmail();
            $success = true;
        }
        else
        {
            $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, $transactionStatus->status);
            $order->save();
            $order->sendNewOrderEmail();

            Mage::getSingleton('checkout/session')->unsQuoteId();
        }


        if(!$success){
            Mage::getSingleton('core/session')->addError(
                Mage::helper('paystack_inline')->__("There was an error processing your payment. Please try again.".$transactionStatus->order_status));
            Mage_Core_Controller_Varien_Action::_redirect('checkout/cart');
        }

    }
}
