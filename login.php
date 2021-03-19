<?php

require_once 'lib/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '177069069676475', // Replace {app-id} with your app id
  'app_secret' => '3eeb2d22bb1ac8d44ad4f5d77b114d5e',
  'default_graph_version' => 'v3.0',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://dc.proscore.com.br/facebook_login/fb-callback.php', $permissions);

print $loginUrl."<BR>";
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>