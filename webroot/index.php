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

if (isset($payload->pull_request)) {
  handle_pull_request($payload);
}
elseif (isset($payload->comment)) {
  if (isset($payload->comment->pull_request_url)) {
    handle_pull_request_comment($payload);
  }
  elseif (isset($payload->issue->pull_request->html_url)) {
    handle_issue_comment($payload);
  }
}
