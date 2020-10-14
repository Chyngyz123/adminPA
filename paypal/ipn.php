<?php
include('../includes/crud.php');
include('../includes/custom-functions.php');

$db = new Database();
$db->connect();
$fn = new custom_functions();
$data = $fn->get_settings('payment_methods', true);


// Database settings. Change these for your database configuration.

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'email' => $data['paypal_business_email'],
    'return_url' => DOMAIN_URL . 'paypal/payment_status.php',
    'cancel_url' => DOMAIN_URL . 'paypal/payment_status.php?tx=failure',
    'notify_url' => DOMAIN_URL . 'paypal/ipn.php'
];

$paypalUrl = ($data['paypal_mode'] == "sandbox") ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Check if paypal request or response
if (isset($_POST["txn_id"])) {
    // Handle the PayPal response.

    // Create a connection to the database.

    // Assign posted variables to local data array.
    $data = [
        'item_name' => $_POST['item_name'],
        'item_number' => $_POST['item_number'],
        'payment_status' => $_POST['payment_status'],
        'payment_amount' => $_POST['mc_gross'],
        'payment_currency' => $_POST['mc_currency'],
        'txn_id' => $_POST['txn_id'],
        'receiver_email' => $_POST['receiver_email'],
        'payer_email' => $_POST['payer_email'],
        'custom' => $_POST['custom'],
    ];
    file_put_contents('data.txt', print_r($data, true), FILE_APPEND);

    if ($fn->verifyTransaction($_POST)) {
        if (isset($data['payment_status']) && (strtolower($data['payment_status']) == 'completed' || strtolower($data['payment_status']) == 'authorize')) {
            /* Transaction success */
            file_put_contents('data.txt', "Transaction success : " . $data['txn_id'] . " " . PHP_EOL, FILE_APPEND);
        } elseif (isset($data['payment_status']) && (strtolower($data['payment_status']) != 'disabled' || strtolower($data['payment_status']) != 'failed')) {
            /* payment failed or something went wrong, cancel the order */
            $res = $fn->get_data('', 'txn_id = "' . $data['item_number'] . '"', 'transactions');
            if (!empty($res) && isset($res[0]['order_id']) && is_numeric($res[0]['order_id'])) {
                $order_id = $res[0]['order_id'];
                /* cancel order */
                $response = $fn->update_order_status($order_id, 'cancelled', 0);
                file_put_contents('data.txt', "Order update status : " . $response . " " . PHP_EOL, FILE_APPEND);
            }
        }
    } else {
        file_put_contents('data.txt', "Transaction failed: " . $data['txn_id'] . " " . PHP_EOL, FILE_APPEND);
        $res = $fn->get_data('', 'txn_id = "' . $data['item_number'] . '"', 'transactions');
        if (!empty($res) && isset($res[0]['order_id']) && is_numeric($res[0]['order_id'])) {
            $order_id = $res[0]['order_id'];
            /* cancel order */
            $response = $fn->update_order_status($order_id, 'cancelled', 0);
            file_put_contents('data.txt', "Order update status : " . $response . " " . PHP_EOL, FILE_APPEND);
        }
    }
}
