#!/bin/bash

if [ $# -ne 1 ]; then
    echo "Usage: $0 <challenge-name>"
    exit -1
fi

chal_name="${1}"
mkdir "${chal_name}"
mkdir "${chal_name}"/{code,solution}
touch "${chal_name}/Readme.md"
