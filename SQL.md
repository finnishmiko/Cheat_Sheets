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
