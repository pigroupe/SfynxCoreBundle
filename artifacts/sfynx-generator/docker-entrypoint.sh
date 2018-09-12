#!/bin/bash

#
# set debbug
[[ $DEBUG == true ]] && set -x

#
# Run
${*}

#
# https://pear.php.net/manual/en/package.php.php-uml.command-line.php
# Converting from UML/XMI version 1 to 2

if [[ ! -z "$XMI_FILE_PATH" ]]; then
    phpuml ${XMI_FILE_PATH}.xmi -o ${XMI_FILE_PATH}_v2.1.xmi
fi
