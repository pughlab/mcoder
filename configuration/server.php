<?php

$provider = new Stevenmaguire\OAuth2\Client\Provider\Keycloak([
  // the keycloak server's root url and port. Must include protocol (http or https)
  'authServerUrl'             => '',
  'realm'                     => '', // the realm's name
  'clientId'                  => '', // the client ID for the app in keycloak
  'clientSecret'              => '', // the client secret (available in keycloak)
  'redirectUri'               => '', // the URL to redirect to after a successful login
  'encryptionAlgorithm'       => null,
  'encryptionKey'             => null,
  'encryptionKeyPath'         => null
]);

$clientID = "";
$KEYCLOAKDOMAIN = ""; // must include the protocol (http or https)
$MCODERDOMAIN = ""; // must include the protocol (http or https)
$REALM = "";
