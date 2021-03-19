<?php 
session_start();
//unset($_SESSION['face_access_token']);
require_once 'lib/Facebook/autoload.php';

$fb = new \Facebook\Facebook([
  'app_id' => '1644063858976105',
  'app_secret' => '9e95c51b974f1940f2b57faca3a56372',
  'default_graph_version' => 'v2.2',
  //'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();
var_dump($helper);
$permissions = ['email']; // Optional permissions

try {
	if(isset($_SESSION['face_access_token'])){
		$accessToken = $_SESSION['face_access_token'];
	}else{
		$accessToken = $helper->getAccessToken();
	}
	print "<BR> >>".$accessToken."<BR>";
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}


if (! isset($accessToken)) {
	$url_login = 'https://dc.proscore.com.br/facebook_login/face.php';
	$loginUrl = $helper->getLoginUrl($url_login, $permissions);
	print "1<BR>";
}else{
	$url_login = 'https://dc.proscore.com.br/facebook_login/face.php';
	$loginUrl = $helper->getLoginUrl($url_login, $permissions);
	//Usuário ja autenticado
	if(isset($_SESSION['face_access_token'])){
		$fb->setDefaultAccessToken($_SESSION['face_access_token']);
	}//Usuário não está autenticado
	else{
		$_SESSION['face_access_token'] = (string) $accessToken;
		$oAuth2Client = $fb->getOAuth2Client();
		$_SESSION['face_access_token'] = (string) $oAuth2Client->getLongLivedAccessToken($_SESSION['face_access_token']);
		$fb->setDefaultAccessToken($_SESSION['face_access_token']);	
	}
	print "2<BR>";
	try {
		// Returns a `Facebook\FacebookResponse` object
		$response = $fb->get('/me?fields=name, picture, email');
		$user = $response->getGraphUser();
		//var_dump($user);
		
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
}

print htmlspecialchars($loginUrl)."<BR>";
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>

