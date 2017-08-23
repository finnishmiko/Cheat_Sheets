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
