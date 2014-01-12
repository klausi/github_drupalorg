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

//file_put_contents($logfile, print_r(json_decode($_POST['payload']), TRUE) . "\n\n", FILE_APPEND);
//file_put_contents($logfile, print_r($_SERVER, TRUE) . "\n\n", FILE_APPEND);
//file_put_contents($logfile, $signature . "\n\n", FILE_APPEND);

$payload = json_decode($_POST['payload']);
//file_put_contents($logfile, "boing\n\n", FILE_APPEND);
//file_put_contents($logfile, print_r($payload, TRUE) . "\n\n", FILE_APPEND);

switch ($payload->action) {
  case 'synchronize':
    $branch_name = $payload->pull_request->head->ref;
    $matches = array();

    // Only look at branch names that end in an issue number.
    if (preg_match('/([0-9]+)$/', $branch_name, $matches)) {
      post_comment($matches[0], 'test comment', '/home/klausi/web/github_drupalorg/test.txt');

      $diff_url = $payload->pull_request->diff_url;
    }

    break;
}
