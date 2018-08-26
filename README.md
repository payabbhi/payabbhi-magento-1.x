## Payabbhi Plugin for Magento v1.x

Payabbhi plugin for Magento enables zero-code integration with [Payabbhi Checkout ](https://payabbhi.com/docs/checkout) via [Payabbhi PHP Library](https://github.com/payabbhi/payabbhi-php).

This plugin is compatible with Magento 1.x.x.

### Pre-requisites

- Magento 1.x
- PHP >= 5.4.45 and <= 5.6.26

### Installation

Make sure you have signed up for your [Payabbhi Account](https://payabbhi.com/docs/account) and downloaded the [API keys](https://payabbhi.com/docs/account/#api-keys) from the [Portal](https://payabbhi.com/portal).

Clone/Download this repository and merge/copy the following folders in your Magento installation directory:

- app
- js
- payabbhi-php

### Configuration

1. Navigate to `Magento Dashboard` -> `System` -> `Configuration` -> `Payment Methods`
2. Click on `Payabbhi (Card / NetBanking / UPI / Wallet)` to configure `Payabbhi`:
  - [Access ID](https://payabbhi.com/docs/account/#api-keys)
  - [Secret Key](https://payabbhi.com/docs/account/#api-keys)
  - [payment_auto_capture](https://payabbhi.com/docs/api/#create-an-order)
  - `Enabled` - Change to `Yes`
  - `Title` - Payabbhi description displayed on Magento Checkout page.
  - `Override Merchant Name` - Merchant name to be displayed on Payabbhi Checkout Form.


```Magento Cache may need to be cleared from the Admin panel (`System` -> `Cache Management`), in case `Payabbhi` does not appear as per #2 above.```

Your Magento installation is now enabled for Payments acceptance via [Payabbhi Checkout](https://payabbhi.com/docs/checkout).
