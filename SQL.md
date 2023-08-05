# [MySQL](https://dev.mysql.com/doc/mysql-getting-started/en/)

Azure database for MySQL default collation: latin1_swedish_ci
Change it to utf8_unicode_ci for Wordpress use.

```
docker exec -it mariadb_container_name mysql -u username --password=password

help
SHOW DATABASES;
CREATE DATABASE databasename;
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

# Powershell 7
# Note the usage of dollar sign
MARIADB=`docker ps -q --filter="name=devcontainer_name_db_1" --format "{{.Names}}"`

echo "show databases;" | docker exec -i $MARIADB mysql -u root --password=password
echo "use databasename;select * from tablename" | docker exec -i $MARIADB mysql -u root --password=password

DATABASE="databasename"

echo "drop database $DATABASE; CREATE DATABASE $DATABASE CHARACTER SET utf8 COLLATE utf8_unicode_ci;" |  docker exec -i $MARIADB mysql -u root --password=password
cat /tmp/databasedump.sql | docker exec -i $MARIADB mysql -u root --password=password $DATABASE

# Create database dump:
docker exec -i $MariaDBInstance /bin/bash -c "mysqldump -u mariadb --password=mariadb mariadb > /tmp/databasedump.sql"
docker cp $MariaDBInstance":/tmp/databasedump.sql" .
```

Change wp_options table's home and siteurl values to devcontainer's url:

```powershell
$MariaDBInstance = (docker ps -q --filter="name=mariadb_db" --format "{{.Names}}") | Out-String | ForEach-Object {$_.Trim()}
docker exec -it $MariaDBInstance mysql -u username -p
docker exec -it $MariaDBInstance mysql -u username --password=password

SELECT * FROM wp_options WHERE option_name = 'home';
UPDATE wp_options SET option_value="http://localhost:8080" WHERE option_name = "home";

SELECT * FROM wp_options WHERE option_name = 'siteurl';
UPDATE wp_options SET option_value="http://localhost:8080" WHERE option_name = "siteurl";
```

```SQL
CREATE USER 'username'@'host' IDENTIFIED BY 'password';
GRANT PRIVILEGE ON database.table TO 'username'@'host';
GRANT ALL PRIVILEGES ON databasename.* TO 'username'@'%';

# List database users
SELECT User, Host, Password FROM mysql.user;
SHOW GRANTS FOR 'username'@'host';
SHOW GRANTS FOR 'username'@'%';

# Revoke permissions

REMOVE permission1, permission2, permission3 ON databasename.* FROM 'username'@'localhost';

# This command reloads the tables with the new users and privileges included.
FLUSH PRIVILEGES;
```
