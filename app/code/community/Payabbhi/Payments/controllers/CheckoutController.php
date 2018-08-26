<?php

class Payabbhi_Payments_CheckoutController extends Mage_Core_Controller_Front_Action
{
  public function indexAction() {
    $session = Mage::getSingleton('checkout/session');
    $order = Mage::getModel('sales/order');
    $order->loadByIncrementId($session->getLastRealOrderId());

    $this->loadLayout();
    $payabbhiBlock = $this->getLayout()
                      ->createBlock('payabbhi_payments/checkout')
                      ->setOrder($order);

    $this->getLayout()->getBlock('content')->append($payabbhiBlock);
    Mage::app()->getLayout()->getBlock('head')->addJs('payabbhi/payabbhi-utils.js');

    $this->renderLayout();
  }

  public function successAction() {
    $helper = Mage::helper('payabbhi_payments');
    $result = $helper->verifyPaymentResponse();
    Mage::getSingleton('core/session')->unsPayabbhiOrderID();
    if ($result) {
      $this->_redirect('checkout/onepage/success');
    } else {
        $this->_redirect('checkout/onepage/failure');
    }
  }

}
?>
