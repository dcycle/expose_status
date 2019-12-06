#!/bin/bash
#
# Generate html from md. Useful for updating the description on Drupal.
#
set -e

docker run --rm -v "$(pwd):/app/code" \
  dcycle/md2html:1 -t html5 README.md -o README.html
