function PayabbhiUtils(options) {
  this.options = options;
  this.placeOrder = function(onSuccessHandler)
  {
    this.options.handler = onSuccessHandler;
    var checkout = new Payabbhi(this.options);
    checkout.open();
  }
}
