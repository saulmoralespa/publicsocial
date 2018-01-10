<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 11/12/17
 * Time: 09:15 PM
 */

class GoogleShortener
{
	public function shortener($url)
	{
		$postData = array('longUrl' => $url);
		$jsonData = json_encode($postData);



		$curlObj = curl_init();
		curl_setopt($curlObj, CURLOPT_URL, "https://www.googleapis.com/urlshortener/v1/url?key=".API_KEY_SHOTENER);
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

		$response = curl_exec($curlObj);

		$json = json_decode($response);
		curl_close($curlObj);

		if(isset($json->error)){
			return array('status' => false, 'message'=> $json->error->message);
		}else{
			return array('status' => true, 'url'=> $json->id);
		}
	}
}