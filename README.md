Github Pull request to drupal.org synchronization
=================================================

This is an experiment meant for Drupal core development. The goal is to use
Github Pull requests which are automatically converted into patches that are
synced to the drupal.org issue queue upon changes.

[klausi's Drupal repository on Github](https://github.com/klausi/drupal) is
hooked up to this synchronization script, so you need to create pull requests
against klausi/drupal on Github. The repository is updated automatically every
30 minutes with commits on 8.x, 7.x and 6.x.

Usage
-----

* Fork Drupal core from https://github.com/klausi/drupal
* Clone your fork locally and create a Git branch where you develop your
  changes. The branch name should end in a number which represents the node ID
  on drupal.org. Example: comment-validation-1234567
* Create a Pull Request against klausi/drupal. This will trigger the webhook
  and a comment should be posted automatically to the drupal.org issue.
* Whenever you push new commits to the branch that is connected to the pull
  request an updated patch will be posted to the drupal.org issue.
* You can comment on the pull request diff or on the pull request issue on
  Github and your comment will also be posted to the drupal.org issue.


Setup your own synchronizer script
----------------------------------

* Clone this repository on your public reachable server. Configure your web
  server so that the webroot subfolder is your PHP document root.
* Copy settings.example.php to settings.php and provide the configuration
  settings.
* Create the webhook subscription from the command line by executing

    php subscribe.php

  You should get the message "Webhook subscription to repository ... successful."


Hacked vendor directory
-----------------------

Unfortunately I had to hack Symfony's BrowserKit to not rename file uploads to
obscure tmp files, that's why the vendor directory is committed. :-(
