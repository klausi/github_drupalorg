<?php

require 'settings.php';
require 'vendor/autoload.php';

use Guzzle\Http\Client;

$payload = json_encode(array(
  'name' => 'web',
  'active' => TRUE,
  'events' => array('pull_request', 'pull_request_review_comment'),
  'config' => array(
    'url' => $webhook_url,
    'secret' => $secret,
  ),
));

$client = new Client("https://api.github.com");
$response = $client->post("repos/$owner/$repo/hooks", array(
    'Content-type' => 'application/json',
  ), $payload)
  ->setAuth($owner, $password)
  ->send();

if ($response->getStatusCode() == 201) {
  print "Webhook subscription to repository $repo successful.\n";
}
