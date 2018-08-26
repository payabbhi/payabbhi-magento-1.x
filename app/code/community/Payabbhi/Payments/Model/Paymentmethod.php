<?php
class Payabbhi_Payments_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract
{
  const CURRENCY                   = 'INR';
  const ACCESS_ID                  = 'payment/payabbhi/access_id';
  const SECRET_KEY                 = 'payment/payabbhi/secret_key';
  const PAYABBHI_PAYMENT_ID        = 'payment_id';
  const PAYABBHI_ORDER_ID          = 'order_id';
  const PAYABBHI_PAYMENT_SIGNATURE = 'payment_signature';
  const MERCHANT_NAME_OVERRIDE     = 'payment/payabbhi/merchant_name_override';
  const PAYMENT_AUTO_CAPTURE       = 'payment/payabbhi/payment_auto_capture';

  /**
   * @var string name of the method on magento
   */
  protected $_code = 'payabbhi';
  /**
   * @var boolean checks if gateway or not 
   */
  protected $_isGateway               = true;
  // protected $_canRefundInvoicePartial = true;
  // protected $_canUseForMultishipping  = false;

  /**
   * @return string redirect url after order placed
   */
  public function getOrderPlaceRedirectUrl() {
    return Mage::getUrl('payabbhi/checkout/index');
  }
}
?>
