<?php

use Goutte\Client;

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
