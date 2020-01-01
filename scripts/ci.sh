#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

echo '=> Run fast tests.'
./scripts/test.sh

echo '=> Deploy an environment.'
./scripts/deploy.sh

echo '=> Drupal PHPUnit tests.'
./scripts/php-unit-drupal.sh

echo '=> Tests on environment.'
./scripts/test-running-environment.sh

echo '=> Destroy the environment.'
./scripts/destroy.sh
