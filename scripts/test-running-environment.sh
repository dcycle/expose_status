#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"
TOKEN="$(docker-compose exec drupal /bin/bash -c 'drush ev "expose_status_token()"')"

curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status":"issues found; please check"'
