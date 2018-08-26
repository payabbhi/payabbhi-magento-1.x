# Payabbhi PHP library

Make sure you have signed up for your [Payabbhi Account](https://payabbhi.com/docs/account) and downloaded the [API keys](https://payabbhi.com/docs/account/#api-keys) from the [Portal](https://payabbhi.com/portal).


## Requirements

PHP 5.3.3 and later.

## Manual Installation

For manual installation, include `init.php` in your code.

```php
require_once('/path/to/payabbhi-php/init.php');
```

## Documentation

Please refer to:
- [PHP Lib Docs](https://payabbhi.com/docs/api/?php)
- [Integration Guide](https://payabbhi.com/docs/integration)

## Dependencies

The library requires the following extensions:

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`json`](https://secure.php.net/manual/en/book.json.php)

In case of manual installation, make sure that the dependencies are resolved.

## Getting Started

Simple usage looks like:

```php
$client = new \Payabbhi\Client('access_id', 'secret_key'); // Set your credentials.
$client->setAppInfo('app_name','app_version','app_url'); // You can optionally set your app info using the client before making any request to Payabbhi.Sending app_version and app_url in the request is not mandatory.
/**
 * Orders
 */
$order = $client->order->create(array('merchant_order_id' => $merchantOrderID, 'amount' => $amount, 'currency' => $currency)); // Creates order
$order = $client->order->retrieve($orderId); // Returns a particular order
$orders = $client->order->all(array('count' => 2)); // Returns collection of orders  with filter params
$payments = $client->order->retrieve($orderId)->payments(); // Returns collection of payments  for a particular orderID
/**
 * Payments
 */
$payments = $client->payment->all(); // Returns collection of payments  with no filter params
$payment = $client->payment->retrieve($id); // Returns a particular payment
$payment->capture(); // Captures a payment
$refund = $payment->refund(); // Fully Refunds a payment
$refunds = $payment->refunds(); // Returns collection of refunds for a particular paymentID with optional filter params
/**
 * Refunds
 */
$fullRefund = $client->refund->create($paymentID); // Creates refund for a payment
$partialRefund = $client->refund->create($paymentID, array('amount'=>$refundAmount)); // Creates partial refund for a payment
$refunds = $client->refund->all(array('count' => 2)); // Returns collection of orders  with filter params
$refund = $client->refund->retrieve($refundId); // Returns a particular refund

/**
 * Utility function of verifying payment signature
 */
$attributes = array(
                  'payment_id'        => $payment_id,
                  'order_id'          => $order_id,
                  'payment_signature' => $payment_signature
                );
$client->utility->verifyPaymentSignature($attributes);

// To get the payment details
echo $payment->amount;
echo $payment->currency;
// And so on for other attributes
```

## Tests

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), set ACCESS_ID and SECRET_KEY as environment variable then you can run the test suite:

```bash
$ export ACCESS_ID="<access-id>"
$ export SECRET_KEY="<secret-key>"
$ ./vendor/bin/phpunit
```

Or to run an individual test file:

```bash
$ export ACCESS_ID="<access-id>"
$ export SECRET_KEY="<secret-key>"
$ ./vendor/bin/phpunit tests/PaymentTest.php
```
