<?php
class Payabbhi_Payments_Block_Checkout extends Mage_Core_Block_Template
{
  /**
   * @var string Contains the template used by payabbhi during magento checkout
   */
  protected $_template = 'payabbhi/checkout.phtml';

  /**
   * @return array of checkout arguments
   */
  public function fields() {
    $order = $this->getOrder();
    $merchantOrderID = $order->getRealOrderId();
    $orderAmount = (int) (round($order->getBaseGrandTotal(), 2) * 100);
    $bA = $order->getBillingAddress();

    $helper = Mage::helper('payabbhi_payments');
    $payabbhiOrderID = $helper->createOrder($order);

    $checkoutArgs = array(
      'access_id'     => Mage::getStoreConfig(Payabbhi_Payments_Model_Paymentmethod::ACCESS_ID),
      'order_id'      => $payabbhiOrderID,
      'amount'        => $orderAmount,
      'name'          => Mage::getStoreConfig(Payabbhi_Payments_Model_Paymentmethod::MERCHANT_NAME_OVERRIDE),
      'description'   => 'Order #' . $merchantOrderID,
      'prefill'     => array(
        'name'      => $bA->getFirstname() . ' ' . $bA->getLastname(),
        'email'     => $order->getData('customer_email') ?: '',
        'contact'   => $bA->getTelephone() ?: ''
      ),
      'notes'       => array(
        'merchant_order_id' => $merchantOrderID
      )
    );
    return $checkoutArgs;
  }

  /**
   * @return string return url
   */
  public function getReturnUrl() {
    return Mage::getUrl('payabbhi/checkout/success');
  }

}
?>
