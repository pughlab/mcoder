<?php

session_start();
require 'vendor/autoload.php';
include('configuration/server.php');

unset($_SESSION['oauth2state']);
session_destroy();
$url = $provider->getLogoutUrl();
$uri = parse_url($url);


header("Location: $KEYCLOAKDOMAIN/realms/$REALM/protocol/openid-connect/logout?redirect_uri=$MCODERDOMAIN");
?>
