<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 6/12/17
 * Time: 12:32 PM
 */

if (empty($_POST))
	return;
include 'config.php';
$fb_app_id = trim($_POST['fb_app_id']);
$fb_app_secret = trim($_POST['fb_app_secret']);
$fb_fan_page = trim($_POST['fb_fan_page']);
$fb_fan_page_token = trim($_POST['fb_fan_page_token']);
$tw_consumer_key = trim($_POST['tw_consumer_key']);
$tw_consumer_secret = trim($_POST['tw_consumer_secret']);
$tw_access_token = trim($_POST['tw_access_token']);
$tw_access_token_secret = trim($_POST['tw_access_token_secret']);
$in_linkedin_app_client_id = trim($_POST['in_linkedin_app_client_id']);
$in_linkedin_app_client_secret = trim($_POST['in_linkedin_app_client_secret']);
$time_interval = $_POST['time_interval'];

$result = mysqli_query($conn,'SELECT  * FROM public_social_config');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (empty($row)){
	$sql = "INSERT INTO public_social_config (id, fb_app_id, fb_app_secret, tw_consumer_key, tw_consumer_secret,tw_access_token,tw_access_token_secret,in_linkedin_app_client_id,in_linkedin_app_client_secret,time_interval) VALUES (1, '$fb_app_id', '$fb_app_secret', '$tw_consumer_key', '$tw_consumer_secret', '$tw_access_token', '$tw_access_token_secret', '$in_linkedin_app_client_id', '$in_linkedin_app_client_secret', '$time_interval')";
}else{
	$sql = "UPDATE public_social_config SET fb_app_id='$fb_app_id', fb_app_secret='$fb_app_secret', fb_fan_page='$fb_fan_page', fb_fan_page_token='$fb_fan_page_token', tw_consumer_key='$tw_consumer_key', tw_consumer_secret='$tw_consumer_secret', tw_access_token='$tw_access_token', tw_access_token_secret='$tw_access_token_secret', in_linkedin_app_client_id='$in_linkedin_app_client_id', in_linkedin_app_client_secret='$in_linkedin_app_client_secret', time_interval='$time_interval' WHERE id=1";
}

if ($conn->query($sql) === true) {
	echo json_encode(array('status' => true));
} else {
	echo json_encode(array('status' => false, 'error' => $conn->error));
}
$conn->close();