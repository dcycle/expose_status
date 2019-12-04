#!/bin/bash
#
# Destroy the environment.
#
set -e

docker-compose down -v
docker network rm expose_status_default
