#!/bin/bash
# synchronize source code
#
#################################
cd $1
echo "update source code:"
echo `git reset --hard`
echo `git pull --rebase origin master`
