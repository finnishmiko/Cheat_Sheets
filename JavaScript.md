# JavaScript

`window.location.href` is a property that tells you the current URL location of the browser. Changing the value of the property will redirect the page.

`window.open()` is a method to open in a new window

```javascript
/**
 * If HTTP is used, redirect user to HTTPS.
 * Except while testing in localhost.
 */
if (window.location && window.location.protocol !== "https:") {
  if (
    window.location.hostname !== "localhost" &&
    window.location.hostname !== "127.0.0.1"
  ) {
    window.location.href =
      "https:" +
      window.location.href.substring(window.location.protocol.length);
  }
}
```

Validate email reg exp vs. pattern:

```js
function validateEmail(email) {
  // must have a @ and a dot in the email address
  const re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(email);
}
```

```html
<form>
  <input pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required />
  <br />
  <input type="submit" value="Submit" />
</form>
```
