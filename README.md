Expose status report
=====

Every Drupal site has a status report dashboard at /admin/reports/status. This module allows you to expose, as JSON, whether everything is OK or not.

The idea is that if you are managing several Drupal sites, you can set up a dashboard or some Jenkins jobs to keep track of the status of all sites in one place.

Typical usage
-----

(1) install and enable this module.

(2) get the token by running this on the command line:

    drush ev "expose_status_instructions()"

(3) visit the URL provided, which will look like

    {"status":"issues found; please check","generated":"2019-12-04 17:36:02"}

A status of "issues found; please check" means there are errors or warnings. The system will not tell you what they are.

Showing details
-----

Status details can contain sensitive information, so be careful when showing details. To do so enable the included "expose_status_details" module.

Ignoring certain warnings
-----

Let's say you want to ignore the "File system", "Trusted Host Settings" and " Drupal core update status" errors/warnings (not recommended, just for illustration purposes), you can:

(1) enable the included "expose_status_ignore" module.

(2) run `drush ev "print_r(\Drupal::service('system.manager')->listRequirements());"`

(3) find, in the output of the above command, the array key of the items you want to ignore, in this case "file system", "trusted_host_patterns" and "update_core".

(4) visit the URL at example.com/admin/reports/status/expose/*****?ignore=file%20system,trusted_host_patterns,update_core

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

* you can set up a complete local development workspace by downloading this code and running `./scripts/deploy.sh`. You do not need a separate Drupal instance. `./scripts/uli.sh` will provide you with a login link to your environment.
* you can destroy your local environment by running `./scripts/destroy.sh`.
* you can run all tests by running `./scripts/ci.sh`; please make sure all tests before submitting a patch.

Similar modules
-----

* [System Status](https://www.drupal.org/project/system_status) works in a similar way, but it is designed to work with cloud service, and it seems to expose, for a given environment, all version data for core and contrib, whereas Expose status report exports whether at least one issue needing attention.

Automated testing
-----

This module's main page is on Drupal.org; a mirror is kept on GitHub.

Unit tests are performed on Drupal.org's infrastructure and in GitHub. Linting is performed on GitHub using CircleCI and Drupal.org.

Drupal 9 readiness
-----

During the continuous integration process, this code is tested for deprecated code using [Drupal Check](https://github.com/mglaman/drupal-check).
