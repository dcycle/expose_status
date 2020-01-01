#!/bin/bash
#
# Run unit tests à la Drupal. Requires the container to be running.
#
set -e

docker-compose exec drupal /bin/bash -c 'drush en -y simpletest && php core/scripts/run-tests.sh expose_status'
