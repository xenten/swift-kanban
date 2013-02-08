<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rpatel
 * Date: 3/12/12
 * Time: 1:01 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('../stripe/lib/Stripe.php');
Stripe::setApiKey("sk_test_2Iixuv4K1T6RqdzxGba0W3Ke");

// get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// create the charge on Stripe's servers - this will charge the user's card
$charge = Stripe_Charge::create(array(
        "amount" => 1000, // amount in cents, again
        "currency" => "usd",
        "card" => $token,
        "description" => "pr@digite.com")
);