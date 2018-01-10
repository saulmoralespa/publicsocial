<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 6/12/17
 * Time: 01:10 PM
 */
include 'config.php';
if (!$conn)
	die('<h2>Error establecido al conectar con la base de datos:</h2>'.mysqli_connect_error());
$sql = "CREATE TABLE IF NOT EXISTS ".TABLE_CONFIG." (
id int(5) PRIMARY KEY,
fb_app_id VARCHAR(80) NOT NULL default '',
fb_app_secret VARCHAR(80) NOT NULL default '',
fb_fan_page VARCHAR(50) NOT NULL default 0,
fb_fan_page_token VARCHAR(255) NOT NULL default '',
tw_consumer_key VARCHAR(80) NOT NULL default '',
tw_consumer_secret VARCHAR(80) NOT NULL default '',
tw_access_token VARCHAR(80) NOT NULL default '',
tw_access_token_secret VARCHAR(80) NOT NULL default '',
in_linkedin_app_client_id VARCHAR(80) NOT NULL default '',
in_linkedin_app_client_secret VARCHAR(80) NOT NULL default '',
in_linkedin_date_expire_token VARCHAR(255),
token_facebook VARCHAR(255),
date_post_message VARCHAR(50),
time_interval INT NOT NULL default 30
)";

if ($conn->query($sql) === true) {
	header("Location: ./");
	echo json_encode(array('status' => true));
} else {
	echo json_encode(array('status' => false, 'error' => $conn->error));
}
$conn->close();