#!/bin/bash

# only for Russia!!!
COUNTRY_CODE="7"
SPOOLDIR="/srv/data/sms-outgoing/"

USER="smsd"
GROUP="smsd"

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

FILENAME="/tmp/"`date +"%Y.%m.%d-%H:%M:%S"`"_${DST}.XXXXX"
SMS=$(mktemp $FILENAME)
chown :${GROUP} ${SMS}
chmod 0666 ${SMS}

echo "To: ${DST}" >> $SMS
echo "" >> $SMS
echo -en $MSG >> $SMS

mv ${SMS} ${SPOOLDIR}

echo 1
exit 1
