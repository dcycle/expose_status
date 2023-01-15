#!/bin/bash
#
# Run some checks on a running environment
#
set -ex

echo '=> Running tests on a running environment.'
URL="$(docker-compose port webserver 80)"
TOKEN="$(docker-compose exec -T drupal /bin/bash -c 'drush ev "expose_status_token()"')"

echo 'Make sure values returned make sense with base and submodules enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status"'

docker-compose exec -T drupal /bin/bash -c 'drush en -y expose_status_details expose_status_ignore expose_status_severity expose_status_selftest'

echo 'Test: no errors'
curl "$URL/admin/reports/status/expose/$TOKEN?ignore_negate=1&ignore=expose_status_0" | grep '"status":"ok"'

echo 'Test: warning only'
curl "$URL/admin/reports/status/expose/$TOKEN?ignore_negate=1&ignore=expose_status_1" | grep '"status":"issues found; please check"'

echo 'Test: error only'
curl "$URL/admin/reports/status/expose/$TOKEN?ignore_negate=1&ignore=expose_status_2" | grep '"status":"issues found; please check"'

echo 'Test: warning only but warning is ignored'
RESULT=$(curl "$URL/admin/reports/status/expose/$TOKEN?ignore_negate=1&ignore=expose_status_1&only_above_level=1")
echo "$RESULT"
echo "$RESULT"| grep '"status":"ok"'

docker-compose exec -T drupal /bin/bash -c 'drush pmu -y expose_status_details expose_status_ignore expose_status_severity'

echo 'Make sure values returned make sense with only the base module enabled'
curl "$URL/admin/reports/status/expose/not-the-right-token" | grep 'Access denied'
curl "$URL/admin/reports/status/expose/$TOKEN" | grep '"status"'

docker-compose exec -T drupal /bin/bash -c 'drush sset expose_status_token some-token'
echo 'Make sure it is possible to uninstall the module'
docker-compose exec -T drupal /bin/bash -c 'drush pmu -y expose_status'
echo 'Once module is uninstalled, state variable should be deleted'
docker-compose exec -T drupal /bin/bash -c 'drush sget expose_status_token' | grep -v some-token
