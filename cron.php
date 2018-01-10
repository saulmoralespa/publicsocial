<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 6/12/17
 * Time: 06:15 PM
 */
if(!session_id()) {
	session_start();
}
include 'config.php';
require_once('vendor/autoload.php');
require_once 'facebookToken.php';
require_once 'linkedinToken.php';
require_once 'GoogleShortener.php';
use LinkedIn\AccessToken;
use LinkedIn\Exception;

$facebookToken = new facebookToken($conn);
$row = $facebookToken->checkCredentials(true);

$linkedin = new linkedinToken($conn);
$client = $linkedin->checkCredentials();


if (empty($row))
	return;

if (isset($row['date_post_message'])){
	$dateActual = strtotime(date('Y-m-d h:i:s'));
	$datePost = strtotime($row['date_post_message']);
	$interval  = abs($dateActual - $datePost);
	$minutes   = round($interval / 60);
	$interval = $row['time_interval'];
	if ($minutes < $interval)
		return;
}

if (!$conn_posteo)
	return;

$result = mysqli_query($conn_posteo,"SELECT  * FROM ".TABLE_POSTEO);
$row_posteo = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (empty($row_posteo))
	return;

$titulo = $row_posteo['titulo'];
$descripcion = substr($row_posteo['cuerpo'], 0, 100);
$urlImage = $urlSiteImagenes . $row_posteo['foto'];
$idPost = $row_posteo['Id'];
$urlPost = $urlsitePosts . $idPost;

$GoogleShortener = new GoogleShortener();
$shortener = $GoogleShortener->shortener($urlPost);
$urlPost = $shortener['status'] ? $shortener['url'] : $urlPost;


try {
	$facebookToken->checkCredentials()->post(
		'/',
		array (
			'scrape' => 'true',
			'id' => $urlPost
		),
		$row['fb_fan_page_token']
	);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage() . PHP_EOL;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage() . PHP_EOL;
}

if ($linkedin->DateExpireToken() !== 0) {
	$tokenString = file_get_contents('token.json');
	$tokenData = json_decode($tokenString, true);
	$accessToken = new AccessToken($tokenData['token'], $tokenData['expiresAt']);
	$client->setAccessToken($accessToken);

	try{
		$client->post(
			'people/~/shares',
			[
				'comment' => $titulo,
				'content' => [
					'title' => $titulo,
					'description' => $descripcion,
					'submitted-url' => $urlPost,
					'submitted-image-url' => $urlImage,
				],
				'visibility' => [
					'code' => 'anyone'
				]
			]
		);
	}catch (Exception $e){
		echo $e->getMessage() . PHP_EOL;
	}

}
$settings = array(
	'oauth_access_token' => $row['tw_access_token'],
	'oauth_access_token_secret' => $row['tw_access_token_secret'],
	'consumer_key' => $row['tw_consumer_key'],
	'consumer_secret' => $row['tw_consumer_secret']
);

$twitter = new TwitterAPIExchange($settings);

$url = 'https://api.twitter.com/1.1/statuses/update.json';
$requestMethod = 'POST';

$postfields = array(
	'status' => "$titulo $urlPost"
);
echo $twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postfields)
             ->performRequest() . PHP_EOL;

$linkDataFB = array(
	"link" => $urlPost,
	"message" => "$titulo $urlPost"
);

try {
	// Returns a `Facebook\FacebookResponse` object
	$response = $facebookToken->checkCredentials()->post("/{$row['fb_fan_page']}/feed", $linkDataFB, $row['fb_fan_page_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
}

$sql = "DELETE FROM " . TABLE_POSTEO . " WHERE Id=".$idPost;
if ($conn_posteo->query($sql) === true) {
	$date = date('Y-m-d h:i:s');
	$sql = "UPDATE " . TABLE_CONFIG . " SET date_post_message='$date' WHERE id=1";
	$conn->query($sql);
}