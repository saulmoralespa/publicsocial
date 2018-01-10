<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 7/12/17
 * Time: 11:42 AM
 */
class facebookToken
{

	/**
	 * @var
	 */
	protected $_conn;
	public function __construct($conn)
	{
		$this->_conn = $conn;
	}

	public function CreateUrlFBLogin()
	{

			if (empty($this->checkCredentials()))
				return null;
			$helper = $this->checkCredentials()->getRedirectLoginHelper();
			$permissions = ['public_profile','email','user_likes','manage_pages','user_photos','publish_pages','publish_actions','user_managed_groups'];
			return $helper->getLoginUrl($this->full_url( $_SERVER ) . '?social=facebook', $permissions);
	}

	public function checkCredentials($keys = false)
	{
		$result = mysqli_query($this->_conn,"SELECT  * FROM ".TABLE_CONFIG);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (!empty($row)) {
			$fb = new Facebook\Facebook( [
				'app_id'                => $row['fb_app_id'],
				'app_secret'            => $row['fb_app_secret'],
				'default_graph_version' => 'v2.11',
			] );
			if ($keys){
				return $row;
			}else{
				return $fb;
			}
		}else{
			return null;
		}
	}

	public function url_origin($s, $use_forwarded_host=false)
	{

		$ssl = ( ! empty($s['HTTPS']) && $s['HTTPS'] == 'on' ) ? true:false;
		$sp = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/'  )) . ( ( $ssl ) ? 's' : '' );

		$port = $s['SERVER_PORT'];
		$port = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port=='443' ) ) ? '' : ':' . $port;

		$host = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
		$host = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

		return $protocol . '://' . $host;

	}

	public function full_url( $s, $use_forwarded_host=false )
	{
		return $this->url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
	}

	public function getToken()
	{
		if (isset($_REQUEST['code']) && $_GET['social'] == 'facebook') {
			if (empty($this->checkCredentials()))
				return;
			$helper = $this->checkCredentials()->getRedirectLoginHelper();
			try {
				$accessToken = $helper->getAccessToken();
				$token = file_get_contents($this->createurltoken($accessToken));
				$token = json_decode($token);
				$extendToken = $token->access_token;
				$this->saveToken($extendToken);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				echo '<div class="error"><p>Graph returned an error: ' . $e->getMessage() . '</p></div>';
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				echo '<div class="error"><p>Facebook SDK returned an error: ' . $e->getMessage() . '</p></div>';
				exit;
			}
		}
	}

	public function saveToken($token)
	{
		$sql = "UPDATE public_social_config SET token_facebook='$token' WHERE id=1";
		if ($this->_conn->query($sql) === true) {
			header('Location: ./');
		}
	}

	public function createurltoken($token)
	{
		$row = $this->checkCredentials(true);
		$urlextfb = "https://graph.facebook.com/v2.11/oauth/access_token?grant_type=fb_exchange_token&client_id=" . $row['fb_app_id'] . "&client_secret=" . $row['fb_app_secret'] . "&fb_exchange_token=" . $token;
		return $urlextfb;
	}
}