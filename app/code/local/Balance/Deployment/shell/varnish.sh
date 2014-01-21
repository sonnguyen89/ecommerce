#!/bin/bash
# remove cache
#
#################################
echo $2
echo `varnishadm -T $1:6082 ban.url "^/.*"`
echo "done. status 200"
