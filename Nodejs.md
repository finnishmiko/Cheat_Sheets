# [Nodejs](https://nodejs.org/en/)

- Create `package.json` with `npm init`
- `npm up` # Update packages. Run also with `-g`
- `npm outdated` # Check for outdated packages. Run also with `-g`
- Update packages major version: `npm i package@2.0.0`

### Check if variable exists

```javascript
if (typeof variable === 'undefined' || variable === null) {
  // variable is undefined or null
}
```

### Check if object with `id:'10'` is in object array
```javascript
objectArray.find(o => o.id === '10')
// Returns the value of first element in the array that satisfies the testing function
// Otherwise undefined is returned
```

### ES6-syntax:

```javascript
app.get('/', function(){

});

//is same as ES6-syntax:
app.get('/', () => {

});
```


### Promises


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
promise.then(function(result) {
  console.log(result); // "Stuff worked!"
}, function(err) {
  console.log(err); // Error: "It broke"
});
```

https://pouchdb.com/2015/05/18/we-have-a-problem-with-promises.html

```javascript
function step1() {
  console.log('step1(): start');
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log('step1(): end');
      resolve();
    }, 1000);
  });
}

function step2() {
  console.log('step2(): start');
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log('step2(): end');
      resolve();
    }, 1000);
  });
}

function step3() {
  console.log('step3(): start');
  return new Promise(function (resolve) {
    setTimeout(function () {
      console.log('step3(): end');
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

#### [ForEach-loop with promises:](https://stackoverflow.com/questions/31413749/node-js-promise-all-and-foreach)

```javascript
// Sample array
var items = [1, 2, 3, 4, 5];

// Sample async action
var fn = function asyncMultiplyBy2(v){
    return new Promise(resolve => setTimeout(() => resolve(v * 2), 100));
};

// Run the function over all items and create a promises array
var actions = items.map(fn);

// Pass the array of promises
var results = Promise.all(actions);

// Check results
results.then(data =>
    console.log(data) // [2, 4, 6, 8, 10]
);
```


### Check if some library is installed
```js
// Check if some f.ex. OracleDB Node library is installed
function moduleAvailable(name) {
    try {
        require.resolve(name);
        return true;
    } catch(e){}
    return false;
}

if (moduleAvailable('oracledb')) {
	var oracledb = require('oracledb');
	var dbConfig = require('../dbconfig.js');
} else {
	console.log('No OracleDB library installed');
 }
```
