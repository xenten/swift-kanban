<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rpatel
 * Date: 3/12/12
 * Time: 1:27 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('../stripe/lib/Stripe.php');
Stripe::setApiKey("sk_test_2Iixuv4K1T6RqdzxGba0W3Ke");

// get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// create a Customer
$customer = Stripe_Customer::create(array(
        "card" => $token,
        "description" => "pr@digite.com")
);

// charge the Customer instead of the card
Stripe_Charge::create(array(
        "amount" => 1000, # amount in cents, again
        "currency" => "usd",
        "customer" => $customer->id)
);

// save the customer ID in your database so you can use it later
//saveStripeCustomerId($user, $customer->id);
$custid = $customer->id;
echo $custid;
// later
//$customerId = getStripeCustomerId($user);

Stripe_Charge::create(array(
        "amount" => 1500, # $15.00 this time
        "currency" => "usd",
        "customer" => $custid)
);