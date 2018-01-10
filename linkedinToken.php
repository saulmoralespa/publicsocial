<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 8/12/17
 * Time: 12:40 PM
 */
use LinkedIn\Client;
use LinkedIn\Scope;
class linkedinToken
{
	/**
	 * @var
	 */
	protected $_conn;
	public function __construct($bd)
	{
		$this->_conn = $bd;
	}

	public function DateExpireToken()
	{
		$result = mysqli_query($this->_conn,"SELECT in_linkedin_date_expire_token FROM ".TABLE_CONFIG);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (empty($row))
			return 0;
		$primera = $row['in_linkedin_date_expire_token'];
		$segunda = date('d-m-Y');
		$valoresPrimera = explode ("-", $primera);
		$valoresSegunda = explode ("-", $segunda);

		$diaPrimera    = $valoresPrimera[0];
		$mesPrimera  = $valoresPrimera[1];
		$anyoPrimera   = $valoresPrimera[2];

		$diaSegunda   = $valoresSegunda[0];
		$mesSegunda = $valoresSegunda[1];
		$anyoSegunda  = $valoresSegunda[2];

		$diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
		$diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);

		if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
			// "La fecha ".$primera." no es v&aacute;lida";
			return 0;
		}elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
			// "La fecha ".$segunda." no es v&aacute;lida";
			return 0;
		}else{
			return  $diasPrimeraJuliano - $diasSegundaJuliano;
		}
	}

	public function getToken()
	{
		if (isset($_REQUEST['code']) && !isset($_GET['social'])) {
			if (isset($_GET['state']) &&  // and state parameter in place
			    isset($_SESSION['state']) && // and we have have stored state
			    $_GET['state'] === $_SESSION['state'] // and it is our request
			) {
				try {
					$client = $this->checkCredentials();
					$client->setRedirectUrl($_SESSION['redirect_url']);
					$accessToken = $client->getAccessToken($_GET['code']);
					file_put_contents('token.json', json_encode($accessToken));
					$date = date('d-m-Y');
					$expireDate = strtotime('+60 day' , strtotime($date));
					$expireDate = date ( 'd-m-Y' , $expireDate );
					$sql = "UPDATE public_social_config SET in_linkedin_date_expire_token='$expireDate' WHERE id=1";
					if ($this->_conn->query($sql) === true) {
						header('Location: ./');
					}
				} catch (\LinkedIn\Exception $ex) {
					echo $ex->getMessage();
				}
			}
		}
	}

	public function checkCredentials($keys = false)
	{
		$result = mysqli_query($this->_conn,"SELECT  * FROM ".TABLE_CONFIG);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (!empty($row)) {
			$client = new Client(
				$row['in_linkedin_app_client_id'],
				$row['in_linkedin_app_client_secret']
			);
			if ($keys){
				return $row;
			}else{
				return $client;
			}
		}else{
			return null;
		}
	}

	public function createUrlLogin()
	{
		if (empty($this->checkCredentials()))
			return null;
		$client = $this->checkCredentials();
		$_SESSION['state'] = $client->getState();
		$_SESSION['redirect_url'] = $client->getRedirectUrl();
		$scopes = Scope::getValues();
		return $client->getLoginUrl($scopes);

	}
}