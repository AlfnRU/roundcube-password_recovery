#!/bin/bash

# only for Russia!!!
COUNTRY_CODE="7"

USER="smsd"
GROUP="smsd"
SPOOLDIR="/var/spool/sms/outgoing/"

if [ -z "$*" ]; then
    echo "Usage: ./sendsms.sh \"phone number\" \"message\""
    exit -1
fi

DST=$1
MSG=$2

if [[ -z "${DST}" ]]; then
    echo "No destination phone number"
    exit -1
fi

if [[ -z "${MSG}" ]]; then
    echo "No message"
    exit -1
fi

if [[ $DST == +* ]]; then
    DST=${DST:1:11}
fi

if [[ ${#DST} == 10 ]]; then
    DST="$COUNTRY_CODE$DST"
elif [[ ${#DST} == 11 && $DST == 8* ]]; then
    DST="$COUNTRY_CODE${DST:1:10}"
fi

if [[ ${#DST} != 11 ]]; then
    echo "Error in destination phone number"
    exit -1
fi

SMS=$(mktemp /tmp/sms_XXXXXXX)
chown :${GROUP} ${SMS}
chmod 0666 ${SMS}

echo "To: ${DST}" >> $SMS
echo "" >> $SMS
echo $MSG >> $SMS

mv ${SMS} ${SPOOLDIR}

echo 1
exit 1
