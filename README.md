# MMS

## Getting started...

### MySQL Root PW und User PW muss in die folgenden Dateien geschrieben werden:
- secret_mysql_password.txt
- secret_mysql_root_password.txt

### Datenbank aus einem Backup zurück spielen:
```
MYSQL_PASSWORD=$(cat secret_mysql_password.txt)
sudo docker exec -i mms_db_1 mysql -u mms -p${MYSQL_PASSWORD} mms < <dumpfile>.sql
```

## Cronjobs anlegen:

```
# MariaDB Backup
42 18 * * * /<path-to-directory>/cron_backup.sh
```
