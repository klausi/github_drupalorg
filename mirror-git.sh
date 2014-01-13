#!/bin/bash

# Mirrors changes from one git remote repository to another by maintaining a
# local bare clone.
# Example crontab entry for periodic sync every 30 minutes:
# */30 *  *   *   *    /path/to/github_drupalorg/mirror-git.sh &> /dev/null

SOURCE=http://git.drupal.org/project/drupal.git
TARGET=git@github.com:klausi/drupal.git
SYNC_FOLDER=drupal-git

DIRECTORY=$(dirname $0)
cd $DIRECTORY

if [ ! -d "$DIRECTORY/$SYNC_FOLDER" ]; then
  git clone --mirror $SOURCE $SYNC_FOLDER
  git remote add target $TARGET
fi

cd $DIRECTORY/$SYNC_FOLDER
git fetch origin
git push --all target
