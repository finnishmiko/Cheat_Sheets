# [MongoDB](https://www.mongodb.com/)

Mongo's bin-folder:

- Start server: `mongod`
- Start shell: `mongo`
- Manage settings in Windows with Services Manager: `services.msc`

Shell:

- Show commands: `help`
- Show all DBs: `show dbs`
- Show current DB: `db`
- Create or select existing DB: `use <dbname>`
- Show collections: `show collections`
- List objects in collection: `db.collection.find()`
- Delete collection: `db.collection.drop()`
- Show collection contents `db.collection.find().pretty().limit(5)`
- Delete document: `db.collection.deleteOne({<query>})`
- Inser document: `db.collection.insertOne({name: "Name"})`
- Update codument. Both booleans below defaults false. Upsert true means that new document is created if no document match the filter.

```sh
db.collection.findOneAndUpdate(
   {<filter>},
   {$set: {<update>}},
   {
     upsert: <boolean>,
     returnNewDocument: <boolean>
   }
)
```

---

### Docker Mongo

- `docker pull mongo`
- `docker run -d --name some-mongo -p 27017:27017 mongo` # Create Mongo server image
  - To restart automatically add: `--restart unless-stopped`
  - Mongo with authentication add root user: `-e MONGO_INITDB_ROOT_USERNAME=mongoadmin -e MONGO_INITDB_ROOT_PASSWORD=secret`
- `docker stop some-mongo` # stop service
- `docker start some-mongo` # start service
- `docker exec -it some-mongo mongo` # Use Mongo shell
- ` `
- ` ` # Login to Mongo shell with authentication
- `sudo docker exec -it some-mongo bash`
- `mongo -u mongoadmin --authenticationDatabase admin`
- ` `
- ` ` # Create new user to `test` database and restart Mongo
- `use test`
- `db.createUser({user: "myTester", pwd: "password", roles: [ { role: "readWrite", db: "test" }, { role: "read", db: "reporting" }]})`
- `db.adminCommand( { shutdown: 1 } )`

Code:

```javascript
var url = "mongodb://localhost/test";
// i.e. same as with local Mongo.
// With Docker local IP address is needed instead of localhost
```

---

If connection string username or password has `@`-character, it needs to be URL encoded to `%40`.

## Transfer database

```sh
mongodump --db test --collection collection
mongorestore --collection collection --db test dump/
```
