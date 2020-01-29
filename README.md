[![CircleCI](https://circleci.com/gh/dcycle/expose_status.svg?style=svg)](https://circleci.com/gh/dcycle/expose_status)

Expose status report
=====

Every Drupal site has a status report dashboard at /admin/reports/status. **Expose Status Report** allows you to expose, as JSON, whether everything is OK or not.

The idea is that if you are managing several Drupal sites, you can set up a dashboard or some Jenkins jobs to keep track of the status of all sites in one place.

Typical usage
-----

(1) install and enable this module.

(2) get the token by running this on the command line:

    drush ev "expose_status_instructions()"

(3) visit the URL provided, which will look like example.com/admin/reports/status/expose/[token] and yield the following JSON:

    {"status":"issues found; please check","generated":"2019-12-04 17:36:02"}

A status of "issues found; please check" means there are errors or warnings.

Showing details
-----

By default the system will not give you details of issues found, because status details can contain sensitive information. If you need to expose more details, you can do so by enabling the included "expose_status_details" module.

Ignoring certain warnings
-----

Let's say you want to ignore the "File system", "Trusted Host Settings" and "Drupal core update status" errors/warnings (not recommended, just for illustration purposes), you can:

(1) Enable the included "expose_status_ignore" module.

(2) Temporarily enable the included "expose_status_details" module.

(3) Visit example.com/admin/reports/status/expose/***** and identify the items you want to ignore, let's say "file system", "trusted_host_patterns" and "update_core". These are items which have error codes higher than 0.

(4) Based on this, visit the URL at example.com/admin/reports/status/expose/*****?ignore=file%20system,trusted_host_patterns,update_core and make sure the status is now OK.

(5) At this point you can disable the "expose_status_details" module.

Only fail on errors, not warnings
-----

By default both warnings (level 1) and errors (level 2) will trigger a "issues found; please check" status. To only trigger the error above level 1 (to ignore warnings):

(1) enable the included "expose_status_severity" module.

(2) visit the URL at example.com/admin/reports/status/expose/*****?severity=1

Note that the submodules (ignore, severity, details) can be combined.

Extending this module
-----

This module can be extended via the Drupal plugin system. Developers are encouraged to examine the structure of the included expose_status_details, expose_status_ignore and expose_status_severity submodules as a basis for their own extensions. Suggestions for more modules are welcome via the Drupal issue queue.

Local development
-----

If you install Docker on your computer:

* you can set up a complete local development workspace by downloading this codebase and running `./scripts/deploy.sh`. To test with Drupal 9, use `./scripts/deploy.sh 9`. You do not need a separate Drupal instance. `./scripts/uli.sh` will provide you with a login link to your environment.
* you can destroy your local environment by running `./scripts/destroy.sh`.
* you can run all tests by running `./scripts/ci.sh`; please make sure all tests before submitting a patch.

Similar modules
-----

* [System Status](https://www.drupal.org/project/system_status) works in a similar way, but it is designed to work with cloud service, and it seems to expose, for a given environment, all version data for core and contrib, whereas Expose status report exports issues needing attention.

Automated testing
-----

This module's main page is on [Drupal.org](http://drupal.org/project/expose_status); a mirror is kept on [GitHub](http://github.com/dcycle/expose_status).

Unit tests are performed on Drupal.org's infrastructure and in GitHub using CircleCI. Linting is performed on GitHub using CircleCI and Drupal.org. For details please see  [Start unit testing your Drupal and other PHP code today, October 16, 2019, Dcycle Blog](https://blog.dcycle.com/blog/2019-10-16/unit-testing/).

* [Test results on Drupal.org's testing infrastructure](https://www.drupal.org/node/3098822/qa)
* [Test results on CircleCI](https://circleci.com/gh/dcycle/expose_status)

To run automated tests locally, install Docker and type:

    ./scripts/ci.sh

Drupal 9 readiness
-----

As of version 8.x-1.0-beta6, this module is usable, and tested on CircleCI, with Drupal 9. We check for deprecated code using a [dockerized version](https://github.com/dcycle/docker-drupal-check) of [Drupal Check](https://github.com/dcycle/docker-drupal-check).
