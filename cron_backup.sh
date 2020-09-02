#!/usr/bin/bash

SCRIPT_PATH=$(dirname $0)
MYSQL_PASSWORD=$(cat $SCRIPT_PATH/secret_mysql_password.txt)
docker exec mms_db_1 mysqldump -u mms -p${MYSQL_PASSWORD} mms > /data/matthias/backup/serverbackup/root-Server/mms/dump.`date '+%u'`.sql
