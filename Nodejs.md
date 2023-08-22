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

## Node Js project

```JavaScript
npm audit
npm audit fix
```

Stop node process

```JavaScript
process.exit();
```

## JavaScript

- Clone contents of an array:

```JavaScript
const clone = myArray.slice(0)
```

- The `map()` method creates a new array while the `forEach()` method executes a provided function once for each array element

- calculate sum with `reduce`. F.ex. total price from product objects:

```JavaScript
const totalPrice = myArray.reduce((currentValue, item) => currentValue + item.price, 0))
```

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

### Arguments

Read arguments with:

```JavaScript
// args is stging[]
const args = process.argv.slice(2);
```

```bash
# Add arguments to 'npm run' command.
# Note the space after --:
npm run <command> [-- <args>]

# Add arguments to node command:
node [options] script.js [args]
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
objectArray.find((o) => o.id === "10");
// Returns the value of first element in the array that satisfies the testing function
// Otherwise undefined is returned
```

## ES6-syntax

```javascript
app.get("/", function () {});

//is same as ES6-syntax:
app.get("/", () => {});
```

## Promises

### Get apiKey or some other string:

Get the string from cache if it exists of find it's value with some function.

```ts
let testString: string | undefined;
export async function getTestString(): Promise<string> {
  if (!testString) {
    testString = await functionToGetString(parameters);
  }
  return testString;
}
```

Or even better solution is to return promise string. This makes sure the function to get the string is only run once even if the getTestString() is called twice in succession (before first run is ready).

```ts
let testStringPromise: Promise<string> | undefined;
export function getTestString(): Promise<string> {
  if (!testStringPromise) {
    testStringPromise = functionToGetString(parameters);
  }
  return testStringPromise;
}
```

Convert function to promise

```ts
export const promiseFunction = (inputId: string): Promise<{ result: any }> => {
  return new Promise((resolve, reject) => {
    someService.exampleFunction(inputId, async (error: Error, result: any) => {
      if (error) {
        reject(error);
      } else {
        resolve({ result });
      }
    });
  });
};

// User that function in async route:
try {
  const { result } = await promiseFunction("exampleId");
} catch (err) {
  // handle error
}
```

## Promises old

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
  function (result) {
    console.log(result); // "Stuff worked!"
  },
  function (err) {
    console.log(err); // Error: "It broke"
  }
);
```

https://pouchdb.com/2015/05/18/we-have-a-problem-with-promises.html

```javascript
function step1() {
  console.log("step1(): start");
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log("step1(): end");
      resolve();
    }, 1000);
  });
}

function step2() {
  console.log("step2(): start");
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log("step2(): end");
      resolve();
    }, 1000);
  });
}

function step3() {
  console.log("step3(): start");
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log("step3(): end");
      resolve();
    }, 1000);
  });
}

function example() {
  step1().then(step2).then(step3);
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
  return new Promise((resolve) => setTimeout(() => resolve(v * 2), 100));
};

// Run the function over all items and create a promises array
var actions = items.map(fn);

// Pass the array of promises
var results = Promise.all(actions);

// Check results
results.then(
  (data) => console.log(data) // [2, 4, 6, 8, 10]
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

## Measure execution time

```js
console.time("testCode");
// Code to test here
console.timeEnd("testCode");
```

NodeJs backend with `server-timing` package:
  
  ```js
  res.startTime('name', 'details');
  res.endTime('name');
  ```


## Check if array is empty or doesn't exist

```js
if (!array || !array.length) {
  // array empty or does not exist
}
// or
if (!array?.length) {
  // array empty or does not exist
}

// or the opposite

if (array && array.length) {
  // array exists and is not empty
}
// or
if (array?.length) {
  // array exists and is not empty
}
```