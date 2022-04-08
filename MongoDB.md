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
	- Sort in reverse order: `.sort({"_id": -1})`
	- Find all emails with gmail: `.find({"email": /gmail\.com/})`
	- Find all documents with key "email": `.find({email: {$exists: 1}})`
- Delete collection: `db.collection.drop()`
- Show collection contents `db.collection.find().pretty().limit(5)`
- Delete document: `db.collection.deleteOne({<query>})`
	- Delete from all documents key value pair where key is "email": `.updateMany({}, {$unset: {email: {$exists: 1}}})`
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

### Robo 3T

Find items that have a value in search list:

```sh
db.getCollection('locations').find({ name: { $in:["Location 1", "Location 2"] } })
```

Then update those items with some change:

```sh
db.getCollection('locations').update(
   {name: { $in:["Location 1", "Location 2"] }},
   { $set: {"street": "Address 1" }},
   { upsert: false, multi: true }
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

If connection string username or password has `@`-character, it needs to be URL encoded to `%40`.


### Use volume with Mongo

Map volume to a local folder where Mongo's data would be stored if local Mongo were installed. In Ubuntu it is `/var/lib/mongodb`. 

Local driver on Linux accepts options similar to the linux `mount` command.

Docker command to create a volume:

`docker volume create --driver local --opt type=none --opt device=/var/lib/mongodb --opt o=bind mongodb_volume`

To .yml file volume is mapped to data folder inside Mongo container i.e. `/data/db`:

```yml
version: '3.1'
services:
  mongodb:
    image: mongo
    ports:
      - '27017:27017'
    restart: always
    volumes:
      - mongodb_volume:/data/db
volumes:
  mongodb_volume:
    external: true
```

Ports need not be opened unless access to DB is needed from outside of the stack. 


---

## Atlas

Copy DB dump from Docker MongoDB to Atlas

```sh
# Go to local MongoDB Docker container
docker exec -it mongo_db bash

# And create DB dumb. Note that the dump will be created to subfolder with the DB name
mongodump --username=admin --password=<PASSWORD> --authenticationDatabase=admin --port <PORT> --db <DATABASE_NAME> --out /data/db/dumps

# Or create DB dump from Atlas
mongodump --uri mongodb+srv://<USERNAME>:<PASSWORD>@server.mongodb.net/<DATABASE> 

# DB dump from Atlas with Docker Mongo and with one command. Using local folder as Docker volume:
docker run -i --rm -v /tmp/atlas/:/dump mongo mongodump --uri mongodb+srv://<USERNAME>:<PASSWORD>@server.mongodb.net/<DATABASE>

# restore DB dump to Atlas. Note that -d parameter is needed or restore fails.
mongorestore --uri mongodb+srv://<USERNAME>:<PASSWORD>@server.mongodb.net /data/db/dumps/<SUB_FOLDER> -d <DATABASE_NAME>
```

## Transfer database from Docker container

```sh
### ssh into VM where MongoDB docker is run ###
# Check host IP that is used with mongodump with parameter -h <IP>
ip route
# Create a folder for backups and give it needed premissions
mkdir /tmp/backup
chmod 777 /tmp/backup

### From the local computer with ssh tunnel ###
# Create a temporary Mongo container for running its mongodump command
docker run -it --rm -v /tmp/backup/:/dump mongo mongodump --db <db name> -h 172.18.0.1

# Restore DB
mongorestore --db <db name> dump/
```

Bash script example using DB admin user
`sudo su -`
`bash backup_mongo_db.sh`
```bash
FILENAME=/tmp/backup-$(date '+%Y-%m-%d').tar.gz
rm -rf /tmp/backup
mkdir /tmp/backup
chown 999 /tmp/backup
docker run -it --rm -v /tmp/backup/:/dump mongo mongodump --db <db name> -u admin -p <password> -h 172.18.0.1 --authenticationDatabase=admin && \
cd /tmp/backup && \
tar cvfz $FILENAME <db name> && \
rm -rf /tmp/backup
# And finally copy file somewhere f.ex. to Azure Blob storage with azCopy
# cron job can be used to schedule copying
# sudo crontab -e
# 0 5 * * * bash /root/backup_mongo_db.sh
# sudo crontab -l

```

## Mongoose

Mongoose model includes Mongo's functions. To create a plain object use `.toObject()`

#### Populate sub-sub-documents:

```sh
# Example:

testObject = {
	firstLevel: [secondObjectRef],
	text
}

secondObject = {
	secondLevel: thirdObjectRef
}
thirdObject = {
	thirdLevel: thirdLevelDocument
}

# Populated object
testObject = {
	firstLevel: [
		secondLevel: {
			thirdLevel: thirdLevelDocument
		}
	],
	text: 'test'
}
```

Mongoose query with populate:

```javascript
const testObject = await TestObject.findOne({ text: test }).populate({
  path: "firstLevel",
  populate: {
    model: "SecondObject",
    path: "secondObject",
    populate: {
      model: "ThirdLevel",
      path: "thirdLevel"
    }
  }
});
```

### Query with $or operator

Instead of looping items list and making one DB request at each round, create a query list with `map` and then find all from DB with one request with $or operator. F.ex.:

```javascript
const queryList = items.map((i: item) => {
  return {inventory: i.inventory}
})
db.inventory.find( { $or: queryList } )
```
