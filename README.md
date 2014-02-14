Github Pull request to drupal.org synchronization
=================================================

This is an experiment meant for Drupal core development. The goal is to use
Github Pull requests which are automatically converted into patches that are
synced to the drupal.org issue queue upon changes.

[klausi's Drupal repository on Github](https://github.com/klausi/drupal) is
hooked up to this synchronization script, but since posting to drupal.org should
only be done under orginal user accounts you need to setup your own
synchronizer.


Setup your own synchronizer script
----------------------------------

* Clone this repository on your public reachable server. Configure your web
  server so that the webroot subfolder is your PHP document root.
* Copy settings.example.php to settings.php and provide the configuration
  settings.
* Create the webhook subscription from the command line by executing

    php subscribe.php

  You should get the message "Webhook subscription to repository ...
  successful."


Usage
-----

* Find a Drupal core issue on drupal.org that you want to work on. Please note
  that any general discussion about the issue should be on drupal.org, not on
  Github.
* Fork Drupal core from https://github.com/drupal/drupal
* Clone your fork locally and create a Git branch where you develop your
  changes. The branch name should end in a number which represents the node ID
  on drupal.org. Example: comment-validation-1234567
* Make sure that the recent Drupal development branch like 8.x is up to date in
  your fork, so that created patches will apply.
* Create a Pull Request against your own fork (not drupal/drupal!). The base
  fork should be your-name/drupal and a branch such as 8.x, the head fork should
  be your-name/drupal and a branch such as comment-validation-1234567. This will
  trigger the webhook and a comment should be posted automatically to the
  drupal.org issue.
* Whenever you push new commits to the branch that is connected to the pull
  request an updated patch will be posted to the drupal.org issue.


Hacked vendor directory
-----------------------

Unfortunately I had to hack Symfony's BrowserKit to not rename file uploads to
obscure tmp files, that's why the vendor directory is committed. :-(
