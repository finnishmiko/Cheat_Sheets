# [MongoDB](https://www.mongodb.com/)


Mongo's bin-folder:
  * Start server: `mongod`
  * Start shell: `mongo`

Shell:
* Show commands: `help`
* Show all DBs: `show dbs`
* Show current DB: `db`
* Create or select existing DB: `use <dbname>`
* Show collections: `show collections`
* List objects in collection: `db.collection.find()`
* Delete collection: `db.collection.drop()`
* Show collection contents `db.collection.find().pretty().limit(5)`


---
### Docker Mongo
* `docker pull mongo`
* `docker run --name some-mongo -d mongo`

Code:
```javascript
var url = 'mongodb://mongo:27017/test';
// i.e. replace localhost with mongo
```
