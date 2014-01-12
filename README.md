Github Pull request to drupal.org synchronization
=================================================

This is an unfinished experiment meant for Drupal core development. The goal is
to use Github Pull requests which are automatically converted into patches that
are synced to the drupal.org issue queue upon changes. Comments on pull requests
should also be automatically copied to drupal.org with a reference to the pull
request.

Prerequisites
--------------

* Fork Drupal core from https://github.com/drupal/drupal
* Clone your fork locally and create Git branch where you develop your changes.
  The branch name should end in a number which represents the node ID on
  drupal.org. Example: comment-validation-1234567


Setup
-----

* Clone this repository on your public reachable server. Configure your web
  server so that the webroot subfolder is your PHP document root.


Usage
-----

* Create a Pull Request against your own fork of Drupal on Github.

Hacked vendor directory
-----------------------

Unfortunately I had to hack Symfony's BrowserKit to not rename file uploads to
obscure tmp files, that's why the vendor directory is committed. :-(
