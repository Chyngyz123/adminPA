<?php
session_start();
include '../includes/crud.php';
include_once('../includes/variables.php');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');

include_once('verify-token.php');
$db = new Database();
$db->connect();
include_once('../includes/custom-functions.php');
	$fn = new custom_functions;
$response = array();
// print_r($_GET['accesskey']);

if (!isset($_POST['accesskey'])) {
    $response['error'] = true;
    $response['message'] = "Access key is invalid or not passed!";
    print_r(json_encode($response));
    return false;
}
$accesskey = $db->escapeString($fn->xss_clean($_POST['accesskey']));
if ($access_key != $accesskey) {
    $response['error'] = true;
    $response['message'] = "invalid accesskey!";
    print_r(json_encode($response));
    return false;
}

if (!verify_token()) {
    return false;
}

/*
get_user_transactions
    get_user_transactions:1
    user_id:3
    type:transactions/wallet_transactions
    offset:0 {optional}
    limit:5 {optional}
*/
if ((isset($_POST['get_user_transactions'])) && ($_POST['get_user_transactions'] == 1)) {
    $user_id  = (isset($_POST['user_id']) && !empty($_POST['user_id'])) ? $db->escapeString($fn->xss_clean($_POST['user_id'])) : "";
    $type  = (isset($_POST['type']) && !empty($_POST['type'])) ? $db->escapeString($fn->xss_clean($_POST['type'])) : "";
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($fn->xss_clean($_POST['limit'])) : 10;
    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($fn->xss_clean($_POST['offset'])) : 0;

    if (!empty($user_id) && !empty($type)) {
        $sql = "SELECT count(id) as total from $type where user_id=" . $user_id;
        $db->sql($sql);
        $total = $db->getResult();
        $sql = "select * from $type where user_id=" . $user_id . " ORDER BY date_created DESC LIMIT $offset,$limit";
        $db->sql($sql);
        $res = $db->getResult();
        $data = array();
        if (!empty($res)) {
            $response['error'] = false;
            $response['total'] = $total[0]['total'];
            // $response['status'] =$res[0]['type'];
            if($type=='transactions'){
                $response['data'] = $res;
            }
            else{
                $response['data'] = $res;
                for($i=0;$i<count($response['data']);$i++){
                    $response['data'][$i]['status']=$response['data'][$i]['type'];
                    $response['data'][$i]['message']=$response['data'][$i]['message']=='Used against Order Placement'?'Order Successfully Placed':$response['data'][$i]['message'];
                }
            }
            
        } else {
            $response['error'] = true;
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Please pass all the fields!';
    }

    print_r(json_encode($response));
    return false;
}
