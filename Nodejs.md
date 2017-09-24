# [Nodejs](https://nodejs.org/en/)

Create `package.json` with `npm init`

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
