# Hello, world!

Here some stuff for fast integration with ecommpay processing service. It's pre alpha build :) Some examples of usage:

# Setting config:

- It's your local file, you have to move config/config_example.php to config/config.php and set your own secret key

# Example

require 'init.php'; //include SDK

use Gate\Gate;

$gate = new Gate();

//Getting purchase payment page:

$gate->getPurchasePaymentPageUrl(<project_id>, <payment_id>, <payment_amount>);

//Callback handling:

$callback = $gate->handleCallback(<rawdata from gate>);

$callback->getPayment();

$callback->getPaymentStatus();

$callback->getSignature();

...
etc
