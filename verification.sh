#!/bin/bash
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

if [ $# -eq 0 ]; then
    echo "Necesitas enviar un modo"
    exit 1
fi

if [ ! -f "$SCRIPT_DIR/.env" ]; then
    echo ".env no existe"
    exit 1
fi



# Swap mode
# TODO, maybe a better way to do this?
if [ $1 = "enable" ]; then
    sed -i 's/APP_VERIFICATION=false/APP_VERIFICATION=true/g' "$SCRIPT_DIR/.env"
else
    sed -i 's/APP_VERIFICATION=true/APP_VERIFICATION=false/g' "$SCRIPT_DIR/.env"
fi
