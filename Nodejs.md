# [Nodejs](https://nodejs.org/en/)

- [JavaScript](#JavaScript)
- [Command line](#Command-line)
- [Run Node server in Docker](#Run-Node-server-in-Docker)
- [Fetch to work in all browsers with cross-fetch](#Fetch-to-work-in-all-browsers-with-cross-fetch)
- [Check if object with `id:'10'` is in object array](#Check-if-object-with-`id:'10'`-is-in-object-array)
- [ES6-syntax](#ES6-syntax)
- [Promises](#Promises)
- [ForEach-loop with promises](#ForEach-loop-with-promises)
- [Check if some library is installed](#Check-if-some-library-is-installed)

## JavaScript

- Clone contents of an array:

```JavaScript
const clone = myArray.slice(0)
```

- The `map()` method creates a new array while the `forEach()` method executes a provided function once for each array element

## Command line

- Create `package.json` with `npm init`
- `npm up` # Update packages. Run also with `-g`
- `npm outdated` # Check for outdated packages. Run also with `-g`
- List installed packages: `npm ls`. Options `-g` and `--depth=0`
- Update packages major version:

```
npm remove package
npm i --save package
```

## Run Node server in Docker

`Dockerfile`

```docker
FROM node:10-alpine
COPY . /opt/service
WORKDIR /opt/service
ENV NODE_ENV=production
ENV MONGO_SERVER=mongodb://<IP address>:<Port>/<collection>
RUN apk add --no-cache make gcc g++ python && \
	npm install && \
	apk del make gcc g++ python
EXPOSE 3000
CMD npm start

```

Also create `.dockerignore` file and add it folders that are not needed like `node_modules`.

Run:

```sh
docker build -t some-node .
docker run --it -p 3000:3000 --rm some-node
```

## Fetch to work in all browsers with [cross-fetch](https://www.npmjs.com/package/cross-fetch)

```javascript
npm install --save cross-fetch

# Using ES6 modules with Babel or TypeScript
import fetch from 'cross-fetch';

# Using CommonJS modules
const fetch = require('cross-fetch');
```

### Check if variable exists

```javascript
if (typeof variable === "undefined" || variable === null) {
  // variable is undefined or null
}
```

## Check if object with `id:'10'` is in object array

```javascript
objectArray.find(o => o.id === "10");
// Returns the value of first element in the array that satisfies the testing function
// Otherwise undefined is returned
```

## ES6-syntax

```javascript
app.get("/", function() {});

//is same as ES6-syntax:
app.get("/", () => {});
```

## Promises

https://developers.google.com/web/fundamentals/getting-started/primers/promises
Creating a promise:

```js
var promise = new Promise(function(resolve, reject) {
  // do a thing, possibly async, then…

  if (/* everything turned out fine */) {
    resolve("Stuff worked!");
  }
  else {
    reject(Error("It broke"));
  }
});
```

Using that promise:

```js
promise.then(
  function(result) {
    console.log(result); // "Stuff worked!"
  },
  function(err) {
    console.log(err); // Error: "It broke"
  }
);
```

https://pouchdb.com/2015/05/18/we-have-a-problem-with-promises.html

```javascript
function step1() {
  console.log("step1(): start");
  return new Promise(function(resolve) {
    setTimeout(function() {
      console.log("step1(): end");
      resolve();
    }, 1000);
  });
}

function step2() {
  console.log("step2(): start");
  return new Promise(function(resolve) {
    setTimeout(function() {
      console.log("step2(): end");
      resolve();
    }, 1000);
  });
}

function step3() {
  console.log("step3(): start");
  return new Promise(function(resolve) {
    setTimeout(function() {
      console.log("step3(): end");
      resolve();
    }, 1000);
  });
}

function example() {
  step1()
    .then(step2)
    .then(step3);
}
```

Converting an asynchronous function to a promise:

```javascript
// Original function
pdf.create(html).toBuffer((err, buffer) => {
  if (err) {
    return console.log("Error", err);
  }
  console.log("Buffer:", Buffer.isBuffer(buffer));
});

// Promise function
const pdfCreateToBuffer = (
  html: string,
  options?: pdf.CreateOptions | undefined
): Promise<Buffer> => {
  return new Promise((resolve, reject) => {
    pdf.create(html, options).toBuffer((err: Error, buffer: Buffer) => {
      if (err) {
        reject(err);
      } else {
        resolve(buffer);
      }
    });
  });
};

// Used like this within try-catch:
const buffer = await pdfCreateToBuffer(html, options);
console.log("Buffer:", Buffer.isBuffer(buffer));
```

## [ForEach-loop with promises](https://stackoverflow.com/questions/31413749/node-js-promise-all-and-foreach)

```javascript
// Sample array
var items = [1, 2, 3, 4, 5];

// Sample async action
var fn = function asyncMultiplyBy2(v) {
  return new Promise(resolve => setTimeout(() => resolve(v * 2), 100));
};

// Run the function over all items and create a promises array
var actions = items.map(fn);

// Pass the array of promises
var results = Promise.all(actions);

// Check results
results.then(
  data => console.log(data) // [2, 4, 6, 8, 10]
);
```

## Check if some library is installed

```js
// Check if some f.ex. OracleDB Node library is installed
function moduleAvailable(name) {
  try {
    require.resolve(name);
    return true;
  } catch (e) {}
  return false;
}

if (moduleAvailable("oracledb")) {
  var oracledb = require("oracledb");
  var dbConfig = require("../dbconfig.js");
} else {
  console.log("No OracleDB library installed");
}
```
