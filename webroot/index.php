<?php

require '../settings.php';

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
file_put_contents($logfile, "boing\n\n", FILE_APPEND);
file_put_contents($logfile, print_r($payload, TRUE) . "\n\n", FILE_APPEND);

switch ($payload->action) {
  case 'synchronize':


    break;
}
