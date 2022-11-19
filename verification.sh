#!/bin/bash
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

if [ $# -eq 0 ]; then
    echo "Uso: verification.sh MODE (on/off)"
    exit 1
fi

if [ ! -f "$SCRIPT_DIR/.env" ]; then
    echo ".env no existe"
    exit 1
fi


# Swap mode
# TODO, maybe a better way to do this?
if [ $1 = "on" ]; then
    # Turn on verification and disable moderation
    sed -i 's/APP_VERIFICATION=false/APP_VERIFICATION=true/g' "$SCRIPT_DIR/.env"
    sed -i 's/APP_MODERATION=true/APP_MODERATION=false/g' "$SCRIPT_DIR/.env"
else
    # Turn on moderation and disable verification
    sed -i 's/APP_MODERATION=false/APP_MODERATION=true/g' "$SCRIPT_DIR/.env"
    sed -i 's/APP_VERIFICATION=true/APP_VERIFICATION=false/g' "$SCRIPT_DIR/.env"
fi
