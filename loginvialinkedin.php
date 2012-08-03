<?php
define('DL_BASESCRIPT',substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/')));

/* Start session and load library. */
session_start();
require_once(DL_BASESCRIPT . '/oauth/linkedinoauth.php');
require_once(DL_BASESCRIPT . '/lib/linkedinconfig.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new LinkedInOAuth(API_KEY, SECRET_KEY);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
if(isset($_SESSION['redirect_to'])) {
	$_SESSION['redirect_to'] = $_REQUEST['redirect_to'];
}
unset($_SESSION['redirect_to']);

/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to LinkedIn. Refresh the page or try again later.';
}
