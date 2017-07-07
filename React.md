# [React](https://facebook.github.io/react/)

[Create a new React App](#create-a-new-react-app)


# Create a new React App

```sh
npm install -g create-react-app
create-react-app my-app

cd my-app
npm start
```

# JSX is a syntax extension for JavaScript

* Multiline JSX elements needs to be wrapped is parentheses. 
* a JSX expession must have exactly _one_ outermost element
* JSX doesn't add numbers, but you can do that with curly braces, which treats the code like normal JavaScript

```javascript
const myDiv = (
  <div>
    <h1>Hello world</h1>
	<p>2 + 3 = {2+3}</p>
  </div>
);
```
* IF-statements can't be injected into JSX. Write them outside JSX. Either use normal if-else -statements or:
```javascript
// Ternary operator
x ? y : x

// && operator
{ age > 18 && <p>Drive a car</p> }
```


# Rendering JSX
It only updates DOM elements that have changed.

```javascript
// Variable React is JavaScript object called React library
import React from 'react';
import ReactDOM from 'react-dom';

// ReactDOM is a JavaScript library and render is it's method. 
// Render's first argument should evaluate to a JSX expression.
// Second argument defines the element where JSX is appended.
ReactDOM.render(<h1>Hello world</h1>, document.getElementById('app'));
```
