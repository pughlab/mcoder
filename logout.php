<?php

session_start();
require 'vendor/autoload.php';
include('configuration/server.php');

unset($_SESSION['oauth2state']);
session_destroy();
$url = $provider->getLogoutUrl();
$uri = parse_url($url);

//$logout = assertEquals('/auth/realms/testRealm/protocol/openid-connect/logout', $uri['path']);

header('Location: https://$YOURDOMAINFORKEYCLOAK/auth/realms/$YOURREALM/protocol/openid-connect/logout?redirect_uri=https://$YOURDOMAINFORMCODER/index.php');
?>
