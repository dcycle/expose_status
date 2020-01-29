#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

echo '=> Run fast tests.'
./scripts/test.sh

echo '=> Drupal PHPUnit tests.'
./scripts/php-unit-drupal.sh

echo '=> Deploy a Drpual 8 environment.'
./scripts/deploy.sh

echo '=> Tests on Drupal 8 environment.'
./scripts/test-running-environment.sh

echo '=> Destroy the Drupal 8 environment.'
./scripts/destroy.sh

echo '=> Deploy a Drupal 9 environment.'
./scripts/deploy.sh 9

echo '=> Tests on Drupal 9 environment.'
./scripts/test-running-environment.sh

echo '=> Destroy the Drupal 9 environment.'
./scripts/destroy.sh
