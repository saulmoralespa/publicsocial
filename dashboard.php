<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/public_social.css">
    <title>Public Social | Config</title>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="./">PublicSocial</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="./">Inicio</a></li>
        </ul>
    </div>
</nav>
<?php
include 'config.php';
require_once('vendor/autoload.php');
require_once 'facebookToken.php';
if ($conn){
	$result = mysqli_query($conn,'SELECT  * FROM public_social_config');
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$facebookToken = new facebookToken($conn);
	$fb = $facebookToken->checkCredentials();
	if(isset($row['token_facebook'])){
		try {
			// Returns a `Facebook\FacebookResponse` object
			$accounts = $fb->get('/me/accounts', $row['token_facebook']);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		$json = json_decode($accounts->getBody());
		$json = $json->data;
    }
}
?>
<div class="container">
    <div class="row">
        <div class="jumbotron">
            <form method="post" id="form-config" action="updateConfig.php">
                <div class="form-group">
                    <label class="col-form-label" for="fb_app_id">Facebook App Id</label>
                    <input type="text" class="form-control" name="fb_app_id" value="<?php if (isset($row['fb_app_id'])){echo $row['fb_app_id'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="fb_app_secret">Facebook App secret</label>
                    <input type="text" class="form-control" name="fb_app_secret" value="<?php if (isset($row['fb_app_secret'])){echo $row['fb_app_secret'];}?>" required>
                </div>
				<?php
				if (isset($json)) {
					?>
                    <div class="form-group">
                        <label class="col-form-label" for="fb_fan_page">Fanpage a publicar</label>
                        <select class="form-control" name="fb_fan_page" required>
                            <option value="">Seleccione</option>
							<?php
							foreach ($json as $page){
								?>
                                <option value='<?php echo $page->id; ?>' data-fbtoken='<?php echo $page->access_token; ?>' <?php if($page->id == $row['fb_fan_page']) echo "selected"; ?>><?php echo $page->name; ?></option>
								<?php
							}
							?>
                        </select>
                    </div>
					<?php
				}
				?>
                <div class="form-group">
                    <label class="col-form-label" for="tw_consumer_key">Twitter Consumer Key</label>
                    <input type="text" class="form-control" name="tw_consumer_key" value="<?php if (isset($row['tw_consumer_key'])){echo $row['tw_consumer_key'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="tw_consumer_secret">Twitter Consumer Secret</label>
                    <input type="text" class="form-control" name="tw_consumer_secret" value="<?php if (isset($row['tw_consumer_secret'])){echo $row['tw_consumer_secret'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="tw_access_token">Twitter Access Token</label>
                    <input type="text" class="form-control" name="tw_access_token" value="<?php if (isset($row['tw_access_token'])){echo $row['tw_access_token'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="tw_access_token_secret">Twitter Access Token Secret</label>
                    <input type="text" class="form-control" name="tw_access_token_secret" value="<?php if (isset($row['tw_access_token_secret'])){echo $row['tw_access_token_secret'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="in_linkedin_app_client_id">Linkedin app client id</label>
                    <input type="text" class="form-control" name="in_linkedin_app_client_id" value="<?php if (isset($row['in_linkedin_app_client_id'])){echo $row['in_linkedin_app_client_id'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="in_linkedin_app_client_secret">Linkedin app client secret</label>
                    <input type="text" class="form-control" name="in_linkedin_app_client_secret" value="<?php if (isset($row['in_linkedin_app_client_secret'])){echo $row['in_linkedin_app_client_secret'];}?>" required>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="time_interval">Intervalo de tiempo</label>
                    <select class="form-control" name="time_interval" required>
                        <option value="">Seleccione intervalo</option>
                        <option value="5" <?php if (isset($row['time_interval']) && $row['time_interval'] == "5"){echo "selected";}?>>5 minutos</option>
                        <option value="10" <?php if (isset($row['time_interval']) && $row['time_interval'] == "10"){echo "selected";}?>>10 minutos</option>
                        <option value="15" <?php if (isset($row['time_interval']) && $row['time_interval'] == "15"){echo "selected";}?>>15 minutos</option>
                        <option value="20" <?php if (isset($row['time_interval']) && $row['time_interval'] == "20"){echo "selected";}?>>20 minutos</option>
                        <option value="25" <?php if (isset($row['time_interval']) && $row['time_interval'] == "25"){echo "selected";}?>>25 minutos</option>
                        <option value="30" <?php if (isset($row['time_interval']) && $row['time_interval'] == "30"){echo "selected";}?>>30 minutos</option>
                        <option value="35" <?php if (isset($row['time_interval']) && $row['time_interval'] == "35"){echo "selected";}?>>35 minutos</option>
                        <option value="40" <?php if (isset($row['time_interval']) && $row['time_interval'] == "40"){echo "selected";}?>>40 minutos</option>
                        <option value="45" <?php if (isset($row['time_interval']) && $row['time_interval'] == "45"){echo "selected";}?>>45 minutos</option>
                        <option value="50" <?php if (isset($row['time_interval']) && $row['time_interval'] == "50"){echo "selected";}?>>50 minutos</option>
                        <option value="55" <?php if (isset($row['time_interval']) && $row['time_interval'] == "55"){echo "selected";}?>>55 minutos</option>
                        <option value="60" <?php if (isset($row['time_interval']) && $row['time_interval'] == "60"){echo "selected";}?>">60 minutos</option>
                        <option value="65" <?php if (isset($row['time_interval']) && $row['time_interval'] == "65"){echo "selected";}?>>65 minutos</option>
                        <option value="70" <?php if (isset($row['time_interval']) && $row['time_interval'] == "70"){echo "selected";}?>>70 minutos</option>
                        <option value="75" <?php if (isset($row['time_interval']) && $row['time_interval'] == "75"){echo "selected";}?>>75 minutos</option>
                        <option value="80" <?php if (isset($row['time_interval']) && $row['time_interval'] == "80"){echo "selected";}?>>80 minutos</option>
                        <option value="85" <?php if (isset($row['time_interval']) && $row['time_interval'] == "85"){echo "selected";}?>>85 minutos</option>
                        <option value="90" <?php if (isset($row['time_interval']) && $row['time_interval'] == "90"){echo "selected";}?>>90 minutos</option>
                        <option value="95" <?php if (isset($row['time_interval']) && $row['time_interval'] == "95"){echo "selected";}?>>95 minutos</option>
                        <option value="100" <?php if (isset($row['time_interval']) && $row['time_interval'] == "100"){echo "selected";}?>>100 minutos</option>
                        <option value="105" <?php if (isset($row['time_interval']) && $row['time_interval'] == "105"){echo "selected";}?>>105 minutos</option>
                        <option value="110" <?php if (isset($row['time_interval']) && $row['time_interval'] == "110"){echo "selected";}?>>110 minutos</option>
                        <option value="115" <?php if (isset($row['time_interval']) && $row['time_interval'] == "115"){echo "selected";}?>>115 minutos</option>
                        <option value="120" <?php if (isset($row['time_interval']) && $row['time_interval'] == "120"){echo "selected";}?>>120 minutos</option>
                    </select>
                </div>
                <div id="alert-config" class="alert" role="alert">
                    <strong></strong>
                </div>
                <button type="submit" id="save-changes" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="assets/js/public_social.js"></script>
</body>
</html>