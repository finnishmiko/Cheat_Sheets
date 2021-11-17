# [MySQL](https://dev.mysql.com/doc/mysql-getting-started/en/)

```
docker exec -it mariadb_container_name mysql -u username --password=password

help
SHOW DATABASES;
USE databasename;
SHOW TABLES;
SELECT * FROM tablename;
DELETE FROM tablename WHERE id=1;
QUIT
```

Query example to find text with the substring:

```SQL
SELECT * FROM databasename.tablename WHERE Address LIKE "%substr1/%" OR Address LIKE "%substr2%";
```

## db setup with standard powershell

Open devContainer with VS Code, then run following commands in powershell

```powershell
$MariaDBInstance = (docker ps -q --filter="name=devcontainer_name_db_1" --format "{{.Names}}") | Out-String | ForEach-Object {$_.Trim()}

echo "drop database mariadb; CREATE DATABASE mariadb CHARACTER SET utf8 COLLATE utf8_unicode_ci;" |  docker exec -i $MariaDBInstance mysql -u mariadb --password=mariadb

docker cp "..............dump-db.sql" $MariaDBInstance":/tmp/databasedump"

docker exec -i $MariaDBInstance /bin/bash -c "cat /tmp/databasedump | mysql -u mariadb --password=mariadb mariadb && rm /tmp/databasedump"
```