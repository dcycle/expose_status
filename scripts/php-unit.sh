#!/bin/bash
#
# Run unit tests.
#
set -e

docker run -v "$(pwd)":/app phpunit/phpunit \
  --group expose_status

docker run --rm -v "$(pwd):/var/www/html/modules/custom" dcycle/drupal-tester:1 /bin/bash -c "chsh -s /bin/bash www-data && su - www-data -- /scripts/test.sh expose_status"
