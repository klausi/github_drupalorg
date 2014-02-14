<?php

require '../settings.php';
require '../utility.php';

// Verify the request.
if (!isset($_POST['payload']) || !isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
  header('Access denied', TRUE, 403);
  exit;
}

// Verify the signature.
$data = file_get_contents( "php://input" );
$signature = hash_hmac('sha1', $data, $secret);

if ($_SERVER['HTTP_X_HUB_SIGNATURE'] !== "sha1=$signature") {
  header('Access denied', TRUE, 403);
  exit;
}

$payload = json_decode($_POST['payload']);

logger(print_r($payload, TRUE));

// Make sure that a pull request object is set in the received event
// notification and that it was sent on behalf of the repository owner. We don't
// want to post comments to drupal.org on behalf of other users.
if (isset($payload->pull_request) && $payload->sender->login == $owner) {
  handle_pull_request($payload);
}
