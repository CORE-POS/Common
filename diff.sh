#!/bin/sh
#############################################
#
# Script to compare this repo with the same
# directory in the main repo while ignoring
# any irrelevant differences
#
#############################################

if [ ! -d "$1" ]; then
    echo "Usage: diff.sh [/path/to/IS4C/common]"
    exit
fi

diff -bBr \
    "src" "$1"
