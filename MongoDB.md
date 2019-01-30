# [MongoDB](https://www.mongodb.com/)


Mongo's bin-folder:
  * Start server: `mongod`
  * Start shell: `mongo`
  * Manage settings in Windows with Services Manager: `services.msc`

Shell:
* Show commands: `help`
* Show all DBs: `show dbs`
* Show current DB: `db`
* Create or select existing DB: `use <dbname>`
* Show collections: `show collections`
* List objects in collection: `db.collection.find()`
* Delete collection: `db.collection.drop()`
* Show collection contents `db.collection.find().pretty().limit(5)`
* Delete document: `db.collection.deleteOne({<query>})`
* Update codument. Both booleans below defaults false. Upsert true means that new document is created if no document match the filter.
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
* `docker pull mongo`
* `docker run -d --name some-mongo -p 27017:27017 mongo` # Create Mongo server image
* `docker stop some-mongo` # stop service
* `docker start some-mongo` # start service
* `docker exec -it some-mongo mongo` # Use Mongo shell

Code:
```javascript
var url = 'mongodb://localhost/test';
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