<?php
    define('CART_COOKIE','SBwi72UCklwiqzz2');
    define('CART_COOKIE_EXPIRE',time() + (86400 * 30));
    define('TAXRATE', 0.087); //sales tax rate set to 0

    define('CURRENCY','usd');
    define('CHECKOUTMODE', 'TEST'); //change test to live when you are ready to go live

    if(CHECKOUTMODE == 'TEST'){
      define('STRIPE_PRIVATE','sk_test_HJEL1t728OkB8OvQtEwpu2Y7');
      define('STRIPE_PUBLIC','pk_test_nbAguwtUvd2cnjgZdv8RORIQ');
    }

    if(CHECKOUTMODE == 'LIVE'){
      define('STRIPE_PRIVATE','sk_live_nBFi6DPTEnDu0zk9oFYg0pog');
      define('STRIPE_PUBLIC','pk_live_QvoCYYWhpEgULfkt5mGWsGg5');
    }
?>
