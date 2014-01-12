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

$pull_request_url = $payload->pull_request->html_url;

switch ($payload->action) {
  case 'synchronize':
    $comment = 'Some commits were pushed to the <a href="' . $pull_request_url . '">pull request</a>.';
    break;

  case 'opened':
    $comment = 'A <a href="' . $pull_request_url . '">new pull request</a> was opened by <a href="' . $payload->pull_request->user->html_url . '">' . $payload->pull_request->user->login . '</a> for this issue.';
    break;

  default:
    // Unknown action, so we just exit.
    exit;
}

$branch_name = $payload->pull_request->head->ref;
$matches = array();

// Only look at branch names that end in an issue number.
if (!preg_match('/([0-9]+)$/', $branch_name, $matches)) {
  exit;
}

$diff_url = $payload->pull_request->diff_url;
// Download the patch.
$file = download_file($diff_url, "$branch_name.patch");

post_comment($matches[0], $comment, $file);

// Cleanup: remove the downloaded file and the temporary directory it is in.
unlink($file);
rmdir(dirname($file));
