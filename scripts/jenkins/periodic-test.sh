#!/bin/bash
set -e

# shellcheck disable=SC1090
source ~/.docker-host-ssh-credentials

# Create a droplet
DROPLET_NAME=expose-status
IP1=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" \
  "./digitalocean/scripts/new-droplet.sh -aubuntu "$DROPLET_NAME)
# https://github.com/dcycle/docker-digitalocean-php#public-vs-private-ip-addresses
IP2=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" "./digitalocean/scripts/list-droplets.sh" |grep "$IP1" --after-context=10|tail -1|cut -b 44-)
echo "Now determining which of the IPs $IP1 or $IP2 is the public IP"
if [[ $IP1 == 10.* ]]; then
  IP="$IP2";
else
  IP="$IP1";
fi
echo "Created Droplet at $IP"
sleep 90

ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
  root@"$IP" \
  "git clone http://github.com/dcycle/expose_status && \
  cd expose_status && \
  ./scripts/install-docker-and-ci.sh"
