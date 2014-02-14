<?php

// Copy this file to settings.php and adjust to your repository/username.

// The repository owner, which should be you.
$owner = 'klausi';
// Create a personal access token at https://github.com/settings/applications
// and paste it here.
$oauth_token = '1234567890';
// Repository name on github (belonging to you) where pull requests will be
// created.
$repo = 'drupal';
// URL where your webroot/index.php is reachable.
$webhook_url = 'http://example.com/index.php';
// Secret to validate incoming webhook requests (you can invent one).
$secret = 'change me, otherwise you have a security issue!';
// Incoming requests will be saved to a log file to assist debugging.
$logfile = '/home/klausi/github_drupalorg.log';

// Your drupal.org user name which should be used to post patches and update
// issues.
$drupal_user = 'klausi';
// Your drupal.org password.
$drupal_password = 'secret';
