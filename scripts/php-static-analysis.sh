#!/bin/bash
#
# Static analysis.
#
set -e

echo '=> Static analysis of code.'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @phpstan:ignoreError'
docker run --rm \
  -v "$(pwd)":/var/www/html/modules/custom/expose_status \
  dcycle/phpstan-drupal:1 \
  -c /var/www/html/modules/custom/expose_status/scripts/lib/phpstan/phpstan.neon \
  /var/www/html/modules/custom