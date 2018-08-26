<?php

require_once('payabbhi-php/init.php');

class Payabbhi_Payments_Helper_Data extends Mage_Core_Helper_Abstract
{
  /**
   * @param magento order object $order
   * @return string payabbhi orderID
   * @throws payabbhi error
   */
  public function createOrder($order) {
    $payabbhiOrderID = Mage::getSingleton('core/session')->getPayabbhiOrderID();

    try {
      if (($payabbhiOrderID === null) or
            (($payabbhiOrderID and ($this->verifyOrder($payabbhiOrderID, $order)) === false))) {
        $payabbhiOrderID = $this->createPayabbhiOrder($order)->id;
      }
    } catch (Exception $e) {
      Mage::throwException("PAYABBHI_ERROR: " . $e->getMessage());
    }

    $order->addStatusToHistory($order->getStatus(), 'Payabbhi Order ID: ' . $payabbhiOrderID);
    $order->save();

    return $payabbhiOrderID;
  }

  /**
   * @param magento order object $order
   * @return object payabbhi order
   */
  protected function createPayabbhiOrder($order) {
    $orderId = $order->getRealOrderId();
    $orderAmount = (int) (round($order->getBaseGrandTotal(), 2) * 100);
    $client = new \Payabbhi\Client($this->getAccessID(), $this->getSecretKey());
    $payabbhiOrder = $client->order->create(array(
      'merchant_order_id' => $orderId,
      'amount' => $orderAmount,
      'currency' => Payabbhi_Payments_Model_Paymentmethod::CURRENCY,
      'payment_auto_capture' => ($this->getPaymentAutoCapture() === "1")
    ));
    Mage::getSingleton('core/session')->setPayabbhiOrderID($payabbhiOrder->id);
    return $payabbhiOrder;
  }

  /**
   * @param string payabbhi orderID $payabbhiOrderID
   * @param object magento order object $order
   * @return boolean if order is valid or not
   */
  protected function verifyOrder($payabbhiOrderID, $order) {
    try {
      $client = new \Payabbhi\Client($this->getAccessID(), $this->getSecretKey());
      $payabbhiOrder = $client->order->retrieve($payabbhiOrderID);
    } catch(Exception $e) {
        return false;
    }

    $orderArgs = array(
        'id'                  => $payabbhiOrderID,
        'amount'              => (int) (round($order->getBaseGrandTotal(), 2) * 100),
        'currency'            => Payabbhi_Payments_Model_Paymentmethod::CURRENCY,
        'merchant_order_id'   => $order->getRealOrderId(),
    );
    $orderKeys = array_keys($orderArgs);
    foreach ($orderKeys as $key) {
      if ($orderArgs[$key] !== $payabbhiOrder[$key]) {
        return false;
      }
    }
    return true;
  }

  /**
   * @return boolean if payment signature is verified or not
   */
  public function verifyPaymentResponse() {
    $requestFields = Mage::app()->getRequest()->getPost();
    $session = Mage::getSingleton('checkout/session');
    $order = Mage::getModel('sales/order');
    $orderID = $session->getLastRealOrderId();
    $order->loadByIncrementId($orderID);
    $success = false;

    $payabbhiPaymentID = $requestFields[Payabbhi_Payments_Model_Paymentmethod::PAYABBHI_PAYMENT_ID];
    $payabbhiOrderID = $requestFields[Payabbhi_Payments_Model_Paymentmethod::PAYABBHI_ORDER_ID];
    $payabbhiPaymentSignature = $requestFields[Payabbhi_Payments_Model_Paymentmethod::PAYABBHI_PAYMENT_SIGNATURE];

    if (!empty($orderID) and !empty($payabbhiPaymentID)) {
      $amount = $order->getBaseGrandTotal();
      $errorMessage = 'PAYABBHI_ERROR: Payment to Payabbhi Failed.';

      $client = new \Payabbhi\Client($this->getAccessID(), $this->getSecretKey());
      $attributes = array(
        'payment_id'        => $payabbhiPaymentID,
        'order_id'          => $payabbhiOrderID,
        'payment_signature' => $payabbhiPaymentSignature
      );
      try {
          $client->utility->verifyPaymentSignature($attributes);
          $success = true;
      } catch (\Payabbhi\Error $e) {
          $errorMessage .= $e->getMessage();
      }

      if ($success) {
        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
        $order->addStatusHistoryComment("Payment successful via Payabbhi<br/>Payabbhi Payment ID: $payabbhiPaymentID<br/>Payabbhi Order ID: $payabbhiOrderID");
        $order->addStatusHistoryComment("Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be processing your order soon. Order Id: $orderID");
        $order->setBaseTotalPaid($amount);
        $order->setTotalPaid($amount);
        $order->save();
      } else {
        $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
        $order->addStatusHistoryComment($errorMessage);
        $order->save();
      }
    } else {
      if (!empty($orderID)) {
        $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
        $order->addStatusHistoryComment("Customer cancelled the payment");
      }
      $order->addStatusHistoryComment("An error occured while processing this payment");
      $order->save();
    }
    return $success;
  }

  /**
   * @return string config variable ACCESS_ID
   */
  protected function getAccessID() {
    return Mage::getStoreConfig(Payabbhi_Payments_Model_Paymentmethod::ACCESS_ID);
  }

  /**
   * @return string config variable SECRET_KEY
   */
  protected function getSecretKey() {
    return Mage::getStoreConfig(Payabbhi_Payments_Model_Paymentmethod::SECRET_KEY);
  }

  /**
   * @return string config variable PAYMENT_AUTO_CAPTURE
   */
  protected function getPaymentAutoCapture() {
    return Mage::getStoreConfig(Payabbhi_Payments_Model_Paymentmethod::PAYMENT_AUTO_CAPTURE);
  }

}
?>
