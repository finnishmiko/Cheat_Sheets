# [React](https://facebook.github.io/react/)
Following [Codecademy's](https://www.codecademy.com) Learn ReactJS course.


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

# React component
* Reusable chunk of code
* Comes from component class
* Naming of class us UpperCamelCase

```javascript
class MyComponent extends React.Component {
  render() {
    return (
      <h1>Hello world</h1>
    );
  }
}
```

# Named exports
Using variables, functions, classes, etc. from one file in another file.

In `file1.js`
```javascript
export var name = ... // or let, const, function or class
export var name2 = ...
```

In `file2.js`
```javascript
import {name, name2} from './file1';
// then use name and name2 normally
```

# Component props

Pass information to React component with an attribute. `<MyClass message="information here" age={5} />`

To display passed information find the component class that is going to receive the information and include `{this.props.name-of-information}` in class's render method's return statement.

If no information is passed, then the default message can be defined with `defaultProps`:
```javascript
MyClass.defaultProps = {
    message: 'Default message here'
};
```


## Naming convention
* If you pass an *event handler* as a prop, there are two names to choose in the parent component. F. Ex.: if you are listening a 'click' event:
	* _Name of the event handler_ = **_handleClick_**
	* _Name of the prop_ that is used to pass the event handler: **_onClick_**


# Component children
Returns everything between `<MyComponent></MyComponent>`
* If nothing is defined i.e. `<MyComponent />` then children is equal to `undefined`
* If there are more than one child then `this.props.children` will return an array of children

# Dynamic information
Can be done with `props` and `state`. 

`State` is not passed from the outside. State is declared inside of a constructor method. React component have to call `super` in their constructor to be set up properly.

```javascript
class Example extends React.Component {
  constructor(props) {
    super(props);
    this.state = { name: 'value' };
  }

  render() {
    return <div></div>;
  }
}

<Example /> // Has state { name: 'value' }
```

Component can change its state with `this.setState()` function. Arguments are _object_ and _callback_, but latter is rarely needed. Function then merges the new object with the component's current state.

Example use case is to call a custom function that wraps a `this.setState()` call. 

```javascript
class Example extends React.Component {
  constructor(props) {
    super(props);
    this.state = { name: 'value' };
	this.methodName = this.methodName.bind(this); // this line is needed
  }

  methodName() {
	  this.setState({name2: 'value2'});
  }

  render() {
    return (
    <div>
      <button onClick={this.methodName}>
        Click me
      </button>
    </div>
    );
  }
}
```

Calling `this.setState()` automatically calls `render()` as soon as the state has changed. Thus `setState()` can not be inside `render()`.