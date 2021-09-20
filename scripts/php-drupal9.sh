#!/bin/bash
#
# Be ready for Drupal 9.
#
set -e

echo "=> Identify deprecated code so we're ready for Drupal 9"
# See https://github.com/dcycle/docker-drupal-check.
docker run --rm -v "$(pwd)":/var/www/html/modules/expose_status dcycle/drupal-check:1 expose_status/src
