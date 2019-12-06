#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"
TOKEN="$(docker-compose exec drupal /bin/bash -c 'drush ev "expose_status_token()"')"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status":"issues found; please check"'

docker-compose exec drupal /bin/bash -c 'drush pmu -y expose_status_details expose_status_ignore expose_status_severity'

echo 'Make sure values returned make sense with only the base module enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status":"issues found; please check"'

docker-compose exec drupal /bin/bash -c 'drush sset expose_status_token some-token'
echo 'Make sure it is possible to uninstall the module'
docker-compose exec drupal /bin/bash -c 'drush pmu -y expose_status'
echo 'Once module is uninstalled, state variable should be deleted'
docker-compose exec drupal /bin/bash -c 'drush sget expose_status_token' | grep -v some-token
