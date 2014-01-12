Github Pull request to drupal.org synchronization
=================================================

This is an unfinished experiment meant for Drupal core development. The goal is
to use Github Pull requests which are automatically converted into patches that
are synced to the drupal.org issue queue upon changes.


Setup
-----

* Clone this repository on your public reachable server. Configure your web
  server so that the webroot subfolder is your PHP document root.
* Copy settings.example.php to settings.php and provide the configuration
  settings.
* Create the webhook subscription from the command line by executing

    php subscribe.php

  You should get the message "Webhook subscription to repository ... successful."


Usage
-----

* Fork Drupal core from https://github.com/drupal/drupal
* Clone your fork locally and create a Git branch where you develop your
  changes. The branch name should end in a number which represents the node ID
  on drupal.org. Example: comment-validation-1234567
* Create a Pull Request against your own fork of Drupal on Github. This will
  trigger the webhook and a comment should be posted automatically on the
  drupal.org issue.
* Whenever you push new commits to the branch that is connected to the pull
  request an updated patch will be posted to the drupal.org issue.

Hacked vendor directory
-----------------------

Unfortunately I had to hack Symfony's BrowserKit to not rename file uploads to
obscure tmp files, that's why the vendor directory is committed. :-(


TODO
----

Comments on pull requests should also be automatically copied to drupal.org with
a reference to the pull request.
