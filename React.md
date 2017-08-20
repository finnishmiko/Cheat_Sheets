# [React](https://facebook.github.io/react/)
Following [Codecademy's](https://www.codecademy.com) Learn ReactJS course:


* [Create a new React App](#create-a-new-react-app)
* [JSX is a syntax extension for JavaScript](#jsx-is-a-syntax-extension-for-javascript)
* [Rendering JSX](#rendering-jsx)
* [React component](#react-component)
* [Named exports](#named-exports)
* [Component props](#component-props)
* [Naming convention](#naming-convention)
* [Component children](#component-children)
* [Dynamic information](#dynamic-information)
* [Programmin pattern](#programmin-pattern)
* [Styles](#styles)
* [Programming pattern: separating _container_ components from _presentational_ components](#programming-pattern-separating-container-components-from-presentational-components)
* [_propTypes_ to validation and documentation](#proptypes-to-validation-and-documentation)
* [Mounting Lifecycle Methods](#mounting-lifecycle-methods)
* [Updating Lifecycle Methods](#updating-lifecycle-methods)
* [Unmounting Lifecycle Methods](#unmounting-lifecycle-methods)


And other items as well:
* [React with Express](#react-with-express)

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



# Programmin pattern

An instance of the stateful ___Parent___ component class is rendered. One stateless child (___Sibling___) component displays the state, and a different stateless child (___Child___) component displays a way to change the state:

```javascript
// Parent:
render() {
  return (
    <div>
      <Child onChange={this.changeName} />
      <Sibling name={this.state.name} />
    </div>
  );
}

```

A _Parent_ defines a function that calls `this.setState`. (Remember to bind it to `this` in constructor) and passes that function down to a _Child_.

```javascript
// Parent
changeName(newName) {
  this.setState({
    name: newName
  });
}
```

_Child_ defines a function that calls the passed-down function, and that can take an _event object_ `e` as an argument (remember to bind it to `this` in constructor):

```javascript
// Child
handleChange(e) {
    const name = e.target.value;
    this.props.onChange(name);
  }
```

The _Child_ uses this new function as an event handler:
```javascript
// Child
<select
  id="names"
  onChange={this.handleChange}>

  <option value="Name1">Name1</option>
  <option value="Name2">Name2</option>
  <option value="Name3">Name3</option>
</select>
```

When an event (selection from dropdown menu) is detected, the _Parent's_ state updates.

The _Parent_ passes down its state, distinct from the ability to change its state, to a different stateless component, ___Sibling___.

That stateless _Sibling_ component class receives the state and displays it.

```javascript
// Sibling
render() {
  const name = this.props.name;
  return (
    <h1>Hey, my name is {name}!</h1>
  );
}
```

# Styles

```javascript
<h1 style={{ color: 'red' }}>Hello world</h1>
```


The outer curly braces inject JavaScript into JSX. The inner curly braces create a JavaScript object literal.

Using styles in variable:
```javascript
const styles = {
  color: 'red',
  background: 'lightblue'
};

 <h1 style={styles}>Hello world</h1>
 ```

In JavaScript style names are written in hyphenated-lowercase, but in React camelCase is used: `'margin-top' --> marginTop`



# Programming pattern: separating _container_ components from _presentational_ components

If a component has to have a `state`, make calculations based on `props` or manage any complex logic, then that component shouldn't also have to render HTML-like JSX.

Presentational component will only have one property: `render()`. This can be rewritten as JavaScript function called a _**stateless functional component**_.

```javascript
// A component class written in the usual way:
export class MyComponentClass extends React.Component {
  render() {
    return <h1>{this.props.title}</h1>;
  }
}

// The same component class, written as a stateless functional component:
export const MyComponentClass = (props) => {
  return <h1>{props.title}</h1>;
}

// Works the same either way:
ReactDOM.render(
	<MyComponentClass />,
	document.getElementById('app')
);
```


# _propTypes_ to validation and documentation

```javascript
MyComponentClass.propTypes = {
  nameOfProperty: React.PropTypes.expected-data-type.isRequired // string, object, bool, number, func, array
};
```


# Mounting Lifecycle Methods
Three mounting lifecycle methods:
- **componentWillMount** gets called right before when a component renders for the first time
- **render**
- **componentDidMount** gets called right after the HTML from render has finished loading

# Updating Lifecycle Methods

Component updates every time it renders, starting with the second render. Five updating lifecycle methods:
- **componentWillReceiveProps(nextProps)** gets called before rendering begins, if the component will receive `props`
- **shouldComponentUpdate(nextProps, nextState)** gets also called before rendering begins. Returns _true_ or _false_. If _false_ then none of the remaining lifecycle methods for that updating period will be called
- **componentWillUpdate(nextProps, nextState)** gets called between shouldComponentUpdate and render. This is used to interact with things outside of the React
- **render**
- **componentDidUpdate(prevProps, prevState)** gets called after any rendered HTML has finished loading


# Unmounting Lifecycle Methods
- **componentWillUnmount(prevProps, prevState)** gets called right before a component is removed from the DOM


# React with Express
* Install Express with _Express-Generator_ to `myApp`-folder
  * change Express port to 3001 since React uses port 3000
  * Run `npm install`
  * Modify f.ex. users-route to return some simple JSON file
* Install React app to `myApp\client`-folder: `create-react-app client`
  * To get client requests to back end work correctly add `"proxy": "http://localhost:3001/",` to `client\package.json`
  * Create a client side request to React's App.js:
```javascript
// It is important to 1) accept JSON and 2) convert the response to JSON before using it. Otherwise proxying to port 3001 does not work
fetch('api/users', {'accept': 'application/json'})
		  .then(res => res.json())
		  .then(users => console.log(users) );
	  }
```
* Install _Concurrently_ to run React and Express at the same time: `npm i --save-dev concurrently`
  * change `myApp\package.json`'s start command to: `"start": "concurrently \"node ./bin/www\" \"cd client && npm start\""`
  * Start both Express and React with `npm start`

# React Router

* Can be implemented with `react-router-dom`. Install it with `npm` and import:

```javascript
import {
	BrowserRouter as Router,
	Route,
	Link
  } from 'react-router-dom';

// Create a link to Login component and route to it:
<Link to="/login">Login</Link>
<Route path="/login" component={Login} />

```

### Passing _props_ to _component_
```html
<!-- Not like this -->
<Route path="/login" isAuthenticated={isAuthenticated} component={Login} />

<!-- This works -->
<Route path="/login" component={ (props) => <Login {...props} isAuthenticated={isAuthenticated} />} />```
