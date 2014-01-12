<?php

use Goutte\Client;
use Guzzle\Http\Client as GuzzleClient;

require 'vendor/autoload.php';

/**
 * Post a comment to drupal.org.
 *
 * @param int $issue_id
 *   The drupal.org node ID to post the comment to.
 * @param string $comment
 *   Text for the comment field.
 * @param string $patch
 *   File name to attach as patch.
 */
function post_comment($issue_id, $comment, $patch) {
  static $client;
  if (!$client) {
    // Perform a user login.
    global $drupal_user, $drupal_password;
    $client = new Client();
    $crawler = $client->request('GET', 'https://drupal.org/user');
    $form = $crawler->selectButton('Log in')->form();
    // $user and $password must be set in user_password.php.
    $crawler = $client->submit($form, array('name' => $drupal_user, 'pass' => $drupal_password));

    $login_errors = $crawler->filter('.messages-error');
    if ($login_errors->count() > 0) {
      logger("Login to drupal.org failed.");
      exit;
    }
  }
  $edit_page = $client->request('GET', "https://drupal.org/node/$issue_id/edit");
  $form = $edit_page->selectButton('Save')->form();

  $form['nodechanges_comment_body[value]']->setValue($comment);
  // We need to HTML entity decode the issue summary here, otherwise we
  // would post back a double-encoded version, which would result in issue
  // summary changes that we don't want to touch.
  $form['body[und][0][value]']->setValue(html_entity_decode($form->get('body[und][0][value]')->getValue(), ENT_QUOTES, 'UTF-8'));

  // There can be uploaded files already, so we need to iterate to the most
  // recent file number.
  $file_nr = 0;
  while (!isset($form["files[field_issue_files_und_$file_nr]"])) {
    $file_nr++;
  }
  $form["files[field_issue_files_und_$file_nr]"]->upload($patch);

  $status = $form['field_issue_status[und]']->getValue();

  // Set the issue to "needs review" if it is not alreay "needs review" or RTBC.
  if ($status != 8 && $status != 14) {
    $form['field_issue_status[und]']->setValue(8);
  }

  $client->submit($form);
}

/**
 * Logs a message to a log file.
 *
 * @global string $logfile
 *   The file name defined in settings.php.
 * @param string $message
 *   The message to log.
 */
function logger($message) {
  global $logfile;
  file_put_contents($logfile, date("Y-m-d H:i:s") . "  $message\n", FILE_APPEND);
}

/**
 * Downloads a URL to a local temporary file.
 *
 * @param string $url
 *   The URL.
 * @param string $file_name
 *   The local file name where this should be stored, without path prefix.
 *
 * @return string
 *   Absolute path to the tempory file.
 */
function download_file($url, $file_name) {
  // Create a temporary directory.
  $temp_file = tempnam(sys_get_temp_dir(), 'github_drupalorg_');
  unlink($temp_file);
  mkdir($temp_file);

  $client = new GuzzleClient();
  $response = $client->get($url)->send();
  $path = "$temp_file/$file_name";
  file_put_contents($path, $response->getBody());
  return $path;
}
